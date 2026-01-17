<?php

namespace Am2tec\Financial\Domain\Enums;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case POSTED = 'posted';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
}
