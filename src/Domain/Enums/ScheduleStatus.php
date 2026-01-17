<?php

namespace Am2tec\Financial\Domain\Enums;

enum ScheduleStatus: string
{
    case ACTIVE = 'active';
    case PAUSED = 'paused';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
}
