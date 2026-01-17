<?php

namespace Am2tec\Financial\Domain\Enums;

enum TitleType: string
{
    case RECEIVABLE = 'receivable';
    case PAYABLE = 'payable';
}
