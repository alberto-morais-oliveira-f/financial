<?php

namespace Am2tec\Financial\Domain\Enums;

enum CategoryType: string
{
    case REVENUE = 'REVENUE';
    case EXPENSE = 'EXPENSE';
    case COST = 'COST';
    case ASSET = 'ASSET';
    case LIABILITY = 'LIABILITY';
    case EQUITY = 'EQUITY';
    case TAX = 'TAX';
}
