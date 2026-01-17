<?php

namespace Am2tec\Financial\Domain\Enums;

enum TitleStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';
}
