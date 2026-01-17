<?php

namespace Am2tec\Financial\Domain\Enums;

enum EntryType: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';
}
