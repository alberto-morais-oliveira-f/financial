<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Am2tec\Financial\Domain\Enums\CategoryType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasUuids;

    protected $table = 'fin_categories';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'parent_uuid',
        'name',
        'slug',
        'type',
        'is_system_category',
        'description',
    ];

    protected $casts = [
        'type' => CategoryType::class,
        'is_system_category' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('financial.table_prefix', 'fin_') . 'categories';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_uuid', 'uuid');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_uuid', 'uuid');
    }
}
