<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RefundModel extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function getTable()
    {
        return config('financial.table_prefix', 'fin_') . 'refunds';
    }

    public function payment()
    {
        return $this->belongsTo(PaymentModel::class, 'payment_id');
    }
}
