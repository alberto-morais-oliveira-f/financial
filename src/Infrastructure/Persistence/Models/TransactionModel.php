<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function getTable()
    {
        return config('financial.table_prefix', 'fin_') . 'transactions';
    }

    public function entries()
    {
        return $this->hasMany(EntryModel::class, 'transaction_id');
    }
}
