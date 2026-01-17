<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Illuminate\Support\Facades\DB;
use Am2tec\Financial\Domain\Contracts\TransactionRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Entry;
use Am2tec\Financial\Domain\Entities\Transaction;
use Am2tec\Financial\Domain\Enums\EntryType;
use Am2tec\Financial\Domain\Enums\TransactionStatus;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Infrastructure\Persistence\Models\EntryModel;
use Am2tec\Financial\Infrastructure\Persistence\Models\TransactionModel;
use RuntimeException;

class EloquentTransactionRepository implements TransactionRepositoryInterface
{
    public function save(Transaction $transaction): Transaction
    {
        DB::transaction(function () use ($transaction) {
            $model = TransactionModel::updateOrCreate(
                ['id' => $transaction->uuid],
                [
                    'reference_code' => $transaction->referenceCode,
                    'description' => $transaction->description,
                    'status' => $transaction->status->value,
                    'metadata' => $transaction->metadata,
                    'created_at' => $transaction->createdAt,
                ]
            );

            foreach ($transaction->getEntries() as $entry) {
                EntryModel::updateOrCreate(
                    ['id' => $entry->uuid],
                    [
                        'transaction_id' => $model->id,
                        'wallet_id' => $entry->walletId,
                        'type' => $entry->type->value,
                        'amount' => $entry->amount->amount,
                        'before_balance' => $entry->beforeBalance?->amount,
                        'after_balance' => $entry->afterBalance?->amount,
                    ]
                );
            }
        });

        $savedTransaction = $this->findById($transaction->uuid);

        if (!$savedTransaction) {
            throw new RuntimeException("Failed to retrieve the transaction after saving.");
        }

        return $savedTransaction;
    }

    public function findById(string $uuid): ?Transaction
    {
        $model = TransactionModel::with('entries')->find($uuid);

        if (!$model) {
            return null;
        }

        $transaction = new Transaction(
            uuid: $model->id,
            referenceCode: $model->reference_code,
            description: $model->description,
            status: TransactionStatus::from($model->status),
            createdAt: \DateTimeImmutable::createFromMutable($model->created_at),
            metadata: $model->metadata ?? []
        );

        foreach ($model->entries as $entryModel) {
            // Assuming all entries in a transaction share the same currency for now, 
            // or we need to fetch the wallet to know the currency.
            // For simplicity, let's assume BRL or fetch from wallet if needed.
            // Ideally, Entry entity should store currency or we fetch it.
            // Here I'll default to BRL to avoid N+1 queries for now, but this needs refinement.
            $currency = Currency::BRL(); 

            $entry = new Entry(
                uuid: $entryModel->id,
                walletId: $entryModel->wallet_id,
                type: EntryType::from($entryModel->type),
                amount: new Money($entryModel->amount, $currency),
                beforeBalance: $entryModel->before_balance ? new Money($entryModel->before_balance, $currency) : null,
                afterBalance: $entryModel->after_balance ? new Money($entryModel->after_balance, $currency) : null
            );
            $transaction->addEntry($entry);
        }

        return $transaction;
    }
}
