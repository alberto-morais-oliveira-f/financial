<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\TransactionRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Transaction;
use Am2tec\Financial\Infrastructure\Persistence\Models\EntryModel;
use Am2tec\Financial\Infrastructure\Persistence\Models\TransactionModel;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class EloquentTransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(TransactionModel $model)
    {
        parent::__construct($model);
    }

    public function register(Transaction $transaction): TransactionModel
    {
        return DB::transaction(function () use ($transaction) {
            /** @var TransactionModel $model */
            $model = $this->updateOrCreate(
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

            $savedTransaction = $this->find($transaction->uuid);

            if (!$savedTransaction) {
                throw new RuntimeException("Failed to retrieve the transaction after saving.");
            }

            return $savedTransaction;
        });
    }
}
