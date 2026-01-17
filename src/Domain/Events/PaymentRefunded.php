<?php

namespace Am2tec\Financial\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Am2tec\Financial\Domain\Entities\Payment;

class PaymentRefunded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Payment $payment
    ) {}
}
