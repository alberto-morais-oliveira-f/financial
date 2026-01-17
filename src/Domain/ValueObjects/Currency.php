<?php

namespace Am2tec\Financial\Domain\ValueObjects;

use InvalidArgumentException;

class Currency
{
    public function __construct(
        public readonly string $code,
        public readonly int $precision = 2
    ) {
        if (strlen($code) !== 3) {
            throw new InvalidArgumentException("Currency code must be 3 characters long.");
        }
    }

    public static function BRL(): self
    {
        return new self('BRL', 2);
    }

    public static function USD(): self
    {
        return new self('USD', 2);
    }

    public function equals(Currency $other): bool
    {
        return $this->code === $other->code && $this->precision === $other->precision;
    }
}
