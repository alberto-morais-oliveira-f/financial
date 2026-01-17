<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class WalletModel extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function getTable()
    {
        return config('financial.table_prefix', 'fin_') . 'wallets';
    }

    public function owner()
    {
        return $this->morphTo();
    }
}
