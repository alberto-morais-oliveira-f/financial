<?php

declare(strict_types=1);

namespace Am2tec\Financial\Domain\Enums;

enum CategoryType: string
{
    case REVENUE = 'REVENUE';
    case EXPENSE = 'EXPENSE';
}
