<?php

namespace Am2tec\Financial\Domain\Enums;

enum WalletStatus: string
{
    case ACTIVE = 'active';
    case FROZEN = 'frozen';
    case CLOSED = 'closed';
}
