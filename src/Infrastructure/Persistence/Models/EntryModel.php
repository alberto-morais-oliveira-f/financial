<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntryModel extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];

    public function getTable()
    {
        return config('financial.table_prefix', 'fin_') . 'entries';
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(TransactionModel::class, 'transaction_id');
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(WalletModel::class, 'wallet_id');
    }

    /**
     * Relacionamento com a Categoria Financeira.
     * Cada lançamento (Entry) pode ser classificado por uma categoria.
     * Esta é a base para a geração de relatórios financeiros (DRE, DFC).
     */
    public function category(): BelongsTo
    {
        // O model de categoria é referenciado diretamente, pois faz parte do núcleo do pacote.
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }
}
