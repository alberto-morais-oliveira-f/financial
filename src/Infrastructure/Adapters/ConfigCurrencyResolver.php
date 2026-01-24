<?php

namespace Am2tec\Financial\Infrastructure\Adapters;

use Am2tec\Financial\Domain\Contracts\CurrencyResolver;
use Am2tec\Financial\Domain\ValueObjects\Currency;

class ConfigCurrencyResolver implements CurrencyResolver
{
    public function resolve(): Currency
    {
        $code = config('financial.currency.default', 'BRL');
        return new Currency($code);
    }
}
