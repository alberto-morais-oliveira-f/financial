<?php

namespace Am2tec\Financial\Infrastructure\Support;

use Am2tec\Financial\Domain\Contracts\CurrencyResolver;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Illuminate\Support\Facades\Config;

class ConfigCurrencyResolver implements CurrencyResolver
{
    public function resolve(): Currency
    {
        $currencyCode = Config::get('financial.default_currency', 'BRL');
        return new Currency($currencyCode);
    }
}
