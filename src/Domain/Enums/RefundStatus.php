<?php

namespace Am2tec\Financial\Domain\Enums;

enum RefundStatus: string
{
    case PENDING = 'pending';
    case PROCESSED = 'processed';
    case FAILED = 'failed';
}
