<?php

namespace Am2tec\Financial\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Am2tec\Financial\Domain\Entities\Transaction;

class TransactionPosted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Transaction $transaction
    ) {}
}
