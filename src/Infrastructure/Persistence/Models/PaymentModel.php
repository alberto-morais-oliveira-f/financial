<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Am2tec\Financial\Domain\Enums\PaymentStatus; // CORREÇÃO
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'integer',
        'status' => PaymentStatus::class, // CORREÇÃO
    ];

    public function getTable()
    {
        return config('financial.table_prefix', 'fin_') . 'payments';
    }
}
