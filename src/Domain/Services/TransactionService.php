<?php

namespace Am2tec\Financial\Domain\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Am2tec\Financial\Domain\Contracts\TransactionRepositoryInterface;
use Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Entry;
use Am2tec\Financial\Domain\Entities\Transaction;
use Am2tec\Financial\Domain\Enums\EntryType;
use Am2tec\Financial\Domain\Enums\TransactionStatus;
use Am2tec\Financial\Domain\Events\TransactionPosted;
use Am2tec\Financial\Domain\Exceptions\InsufficientFundsException;
use Am2tec\Financial\Domain\Exceptions\WalletNotFoundException;
use Am2tec\Financial\Domain\ValueObjects\Money;
use RuntimeException;

class TransactionService
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected WalletRepositoryInterface $walletRepository
    ) {}

    public function transfer(string $fromWalletId, string $toWalletId, Money $amount, string $description): Transaction
    {
        $transaction = DB::transaction(function () use ($fromWalletId, $toWalletId, $amount, $description) {
            $fromWallet = $this->walletRepository->findById($fromWalletId);
            $toWallet = $this->walletRepository->findById($toWalletId);

            if (!$fromWallet || !$toWallet) {
                throw new WalletNotFoundException("One or both wallets not found.");
            }

            if ($fromWallet->balance->amount < $amount->amount) {
                throw new InsufficientFundsException("Insufficient funds in source wallet.");
            }

            $newFromWallet = $fromWallet->withdraw($amount);
            $newToWallet = $toWallet->deposit($amount);

            $this->walletRepository->save($newFromWallet);
            $this->walletRepository->save($newToWallet);

            $transaction = new Transaction(
                uuid: Str::uuid()->toString(),
                referenceCode: Str::upper(Str::random(10)),
                description: $description,
                status: TransactionStatus::POSTED
            );

            $debitEntry = new Entry(
                uuid: Str::uuid()->toString(),
                walletId: $fromWallet->uuid,
                categoryUuid: null,
                supplierUuid: null,
                type: EntryType::DEBIT,
                amount: $amount,
                beforeBalance: $fromWallet->balance,
                afterBalance: $newFromWallet->balance
            );

            $creditEntry = new Entry(
                uuid: Str::uuid()->toString(),
                walletId: $toWallet->uuid,
                categoryUuid: null,
                supplierUuid: null,
                type: EntryType::CREDIT,
                amount: $amount,
                beforeBalance: $toWallet->balance,
                afterBalance: $newToWallet->balance
            );

            $transaction->addEntry($debitEntry);
            $transaction->addEntry($creditEntry);

            $savedTransaction = $this->transactionRepository->save($transaction);

            if (!$savedTransaction) {
                throw new RuntimeException("Failed to save the transaction.");
            }

            TransactionPosted::dispatch($savedTransaction);

            return $savedTransaction;
        });

        if (!$transaction) {
            // Isso pode acontecer se a transação do DB falhar e não lançar uma exceção.
            throw new RuntimeException("The database transaction failed without throwing an exception.");
        }

        return $transaction;
    }
}
