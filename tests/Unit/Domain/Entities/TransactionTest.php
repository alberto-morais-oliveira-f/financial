<?php

namespace Am2tec\Financial\Tests\Unit\Domain\Entities;

use Am2tec\Financial\Domain\Entities\Entry;
use Am2tec\Financial\Domain\Entities\Transaction;
use Am2tec\Financial\Domain\Enums\EntryType;
use Am2tec\Financial\Domain\Enums\TransactionStatus;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Tests\TestCase;

class TransactionTest extends TestCase
{
    public function test_transaction_is_balanced()
    {
        $transaction = new Transaction(
            uuid: '123',
            referenceCode: 'REF001',
            description: 'Test Transaction'
        );

        $money = Money::of(1000, Currency::BRL());

        $debit = new Entry(null, 'wallet-1', EntryType::DEBIT, $money);
        $credit = new Entry(null, 'wallet-2', EntryType::CREDIT, $money);

        $transaction->addEntry($debit);
        $transaction->addEntry($credit);

        $this->assertTrue($transaction->isBalanced());
    }

    public function test_transaction_is_not_balanced()
    {
        $transaction = new Transaction(
            uuid: '123',
            referenceCode: 'REF001',
            description: 'Test Transaction'
        );

        $debit = new Entry(null, 'wallet-1', EntryType::DEBIT, Money::of(1000, Currency::BRL()));
        $credit = new Entry(null, 'wallet-2', EntryType::CREDIT, Money::of(900, Currency::BRL()));

        $transaction->addEntry($debit);
        $transaction->addEntry($credit);

        $this->assertFalse($transaction->isBalanced());
    }

    public function test_cannot_post_unbalanced_transaction()
    {
        $this->expectException(\DomainException::class);

        $transaction = new Transaction(
            uuid: '123',
            referenceCode: 'REF001',
            description: 'Test Transaction'
        );

        $debit = new Entry(null, 'wallet-1', EntryType::DEBIT, Money::of(1000, Currency::BRL()));
        
        $transaction->addEntry($debit);
        $transaction->markAsPosted();
    }
}
