<?php

namespace Am2tec\Financial\Tests\Unit\Domain\ValueObjects;

use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Tests\TestCase;

class MoneyTest extends TestCase
{
    public function test_can_create_money()
    {
        $money = Money::of(1000, Currency::BRL());
        
        $this->assertEquals(1000, $money->amount);
        $this->assertEquals('BRL', $money->currency->code);
    }

    public function test_can_add_money()
    {
        $m1 = Money::of(1000, Currency::BRL());
        $m2 = Money::of(500, Currency::BRL());

        $result = $m1->add($m2);

        $this->assertEquals(1500, $result->amount);
    }

    public function test_cannot_add_different_currencies()
    {
        $this->expectException(\InvalidArgumentException::class);

        $m1 = Money::of(1000, Currency::BRL());
        $m2 = Money::of(500, Currency::USD());

        $m1->add($m2);
    }
}
