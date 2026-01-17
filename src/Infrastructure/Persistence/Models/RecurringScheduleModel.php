<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RecurringScheduleModel extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'next_run_at' => 'datetime',
        'ends_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function getTable()
    {
        return config('financial.table_prefix', 'fin_') . 'recurring_schedules';
    }
}
