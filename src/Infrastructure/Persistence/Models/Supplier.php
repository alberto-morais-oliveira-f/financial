<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Am2tec\Financial\Domain\Contracts\AccountOwner;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Supplier extends Model implements AccountOwner
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'document',
        'phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getTable()
    {
        return config('financial.table_prefix', 'fin_') . 'suppliers';
    }

    // AccountOwner Implementation
    public function getOwnerId(): string|int
    {
        return $this->uuid;
    }

    public function getOwnerType(): string
    {
        return self::class;
    }

    public function getOwnerName(): string
    {
        return $this->name;
    }

    public function getOwnerEmail(): ?string
    {
        return $this->email;
    }
}
