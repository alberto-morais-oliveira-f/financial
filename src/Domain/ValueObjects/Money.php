<?php

namespace Am2tec\Financial\Domain\ValueObjects;

use InvalidArgumentException;
use NumberFormatter;

class Money
{
    public function __construct(
        public readonly int $amount,
        public readonly Currency $currency
    ) {}

    public static function of(int $amount, Currency $currency): self
    {
        return new self($amount, $currency);
    }

    public function add(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->amount - $other->amount, $this->currency);
    }

    private function assertSameCurrency(Money $other): void
    {
        if (! $this->currency->equals($other->currency)) {
            throw new InvalidArgumentException("Currencies must match: {$this->currency->code} vs {$other->currency->code}");
        }
    }

    public function format(): string
    {
        $formatter = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($this->amount / 100, $this->currency->code);
    }
}
