<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TitleModel extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'metadata' => 'array',
    ];

    public function getTable()
    {
        return config('financial.table_prefix', 'fin_') . 'titles';
    }

    public function wallet()
    {
        return $this->belongsTo(WalletModel::class, 'wallet_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_uuid', 'uuid');
    }
}
