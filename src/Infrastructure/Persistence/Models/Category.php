<?php

declare(strict_types=1);

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Am2tec\Financial\Domain\Enums\CategoryType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 *
 * @property string $uuid
 * @property string|null $parent_uuid
 * @property string $name
 * @property string $code
 * @property CategoryType $type
 * @property bool $is_active
 * @property bool $is_system_category
 * @property string|null $description
 */
class Category extends Model
{
    use HasUuids;

    protected $table = 'financial_categories';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'parent_uuid',
        'name',
        'code',
        'type',
        'is_active',
        'is_system_category',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system_category' => 'boolean',
        'type' => CategoryType::class,
    ];

    /**
     * Relacionamento para a categoria pai (self-referencing).
     * Uma categoria pode pertencer a uma categoria pai, formando uma hierarquia.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_uuid', 'uuid');
    }

    /**
     * Relacionamento para as categorias filhas (self-referencing).
     * Uma categoria pode ter várias categorias filhas.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_uuid', 'uuid');
    }

    /**
     * Relacionamento com os lançamentos financeiros (Entries).
     * Uma categoria pode estar associada a muitos lançamentos.
     * Este é o elo que permite a classificação dos dados financeiros.
     */
    public function entries(): HasMany
    {
        $entryModel = config('financial.models.entry', Entry::class);
        return $this->hasMany($entryModel, 'category_uuid', 'uuid');
    }
}
