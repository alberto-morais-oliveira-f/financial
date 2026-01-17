<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Domain\ValueObjects\Currency;

interface CurrencyResolver
{
    public function resolve(): Currency;
}
