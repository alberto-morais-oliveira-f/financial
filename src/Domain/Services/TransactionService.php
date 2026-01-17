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
use Am2tec\Financial\Domain\ValueObjects\Money;

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
                throw new \InvalidArgumentException("One or both wallets not found.");
            }

            if (!$fromWallet->isActive() || !$toWallet->isActive()) {
                throw new \DomainException("One or both wallets are not active.");
            }

            if ($fromWallet->balance->amount < $amount->amount) {
                throw new \DomainException("Insufficient funds in source wallet.");
            }

            $fromWallet->withdraw($amount);
            $toWallet->deposit($amount);

            $this->walletRepository->save($fromWallet);
            $this->walletRepository->save($toWallet);

            $transaction = new Transaction(
                uuid: Str::uuid()->toString(),
                referenceCode: Str::upper(Str::random(10)),
                description: $description,
                status: TransactionStatus::POSTED
            );

            $debitEntry = new Entry(
                uuid: Str::uuid()->toString(),
                walletId: $fromWallet->uuid,
                type: EntryType::DEBIT,
                amount: $amount,
                beforeBalance: $fromWallet->balance->add($amount),
                afterBalance: $fromWallet->balance
            );

            $creditEntry = new Entry(
                uuid: Str::uuid()->toString(),
                walletId: $toWallet->uuid,
                type: EntryType::CREDIT,
                amount: $amount,
                beforeBalance: $toWallet->balance->subtract($amount),
                afterBalance: $toWallet->balance
            );

            $transaction->addEntry($debitEntry);
            $transaction->addEntry($creditEntry);

            $this->transactionRepository->save($transaction);

            return $transaction;
        });

        TransactionPosted::dispatch($transaction);

        return $transaction;
    }
}
