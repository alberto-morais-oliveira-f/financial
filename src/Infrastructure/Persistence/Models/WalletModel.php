<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Am2tec\Financial\Domain\Enums\WalletType;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletModel extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];

    protected $casts = [
        'type' => WalletType::class,
    ];

    public function getTable()
    {
        return config('financial.table_prefix', 'fin_') . 'wallets';
    }

    public function owner()
    {
        return $this->morphTo();
    }

    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => new Money($value ?? 0, new Currency($this->currency ?? 'BRL')),
        );
    }
}
