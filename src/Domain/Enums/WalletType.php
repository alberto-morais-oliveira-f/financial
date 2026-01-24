<?php

namespace Am2tec\Financial\Domain\Enums;

use Am2tec\Financial\Domain\Traits\EnumTrait;

enum WalletType: string
{
    use EnumTrait;
    
    case BANK_ACCOUNT = 'bank_account';
    case CASH = 'cash';
}
