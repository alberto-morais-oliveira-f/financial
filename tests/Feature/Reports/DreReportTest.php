<?php

namespace Am2tec\Financial\Tests\Feature\Reports;

use Am2tec\Financial\Infrastructure\Persistence\Models\Category;
use Am2tec\Financial\Infrastructure\Persistence\Models\EntryModel;
use Am2tec\Financial\Infrastructure\Persistence\Models\TransactionModel;
use Am2tec\Financial\Infrastructure\Persistence\Models\WalletModel;
use Am2tec\Financial\Tests\TestCase;

class DreReportTest extends TestCase
{
    /** @test */
    public function it_can_generate_a_dre_report()
    {
        // 1. Arrange
        $wallet = WalletModel::factory()->create();
        $revenueCategory = Category::factory()->create(['type' => 'revenue', 'name' => 'Sales']);
        $costCategory = Category::factory()->create(['type' => 'cost', 'name' => 'COGS']);
        $expenseCategory = Category::factory()->create(['type' => 'expense', 'name' => 'Rent']);

        // Criar transações e lançamentos
        $transaction1 = TransactionModel::factory()->create(['status' => 'POSTED', 'updated_at' => '2024-01-15 10:00:00']);
        EntryModel::factory()->create([
            'wallet_id' => $wallet->id,
            'transaction_id' => $transaction1->id,
            'category_uuid' => $revenueCategory->uuid,
            'type' => 'credit',
            'amount' => 10000,
        ]);

        $transaction2 = TransactionModel::factory()->create(['status' => 'POSTED', 'updated_at' => '2024-01-16 11:00:00']);
        EntryModel::factory()->create([
            'wallet_id' => $wallet->id,
            'transaction_id' => $transaction2->id,
            'category_uuid' => $costCategory->uuid,
            'type' => 'debit',
            'amount' => 4000,
        ]);

        $transaction3 = TransactionModel::factory()->create(['status' => 'POSTED', 'updated_at' => '2024-01-17 12:00:00']);
        EntryModel::factory()->create([
            'wallet_id' => $wallet->id,
            'transaction_id' => $transaction3->id,
            'category_uuid' => $expenseCategory->uuid,
            'type' => 'debit',
            'amount' => 1500,
        ]);
        
        // Transação fora do período
        $transaction4 = TransactionModel::factory()->create(['status' => 'POSTED', 'updated_at' => '2024-02-01 10:00:00']);
        EntryModel::factory()->create([
            'wallet_id' => $wallet->id,
            'transaction_id' => $transaction4->id,
            'category_uuid' => $revenueCategory->uuid,
            'type' => 'credit',
            'amount' => 5000,
        ]);

        // 2. Act
        $response = $this->getJson(route('financial.reports.dre.show', [
            'start_date' => '2024-01-01 00:00:00',
            'end_date' => '2024-01-31 23:59:59',
        ]));

        // 3. Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'summary' => [
                'revenue',
                'costs',
                'gross_profit',
                'expenses',
                'taxes',
                'operating_profit',
            ],
            'detailed' => [
                '*' => [
                    'category_id',
                    'category_name',
                    'category_type',
                    'parent_id',
                    'total_amount',
                ]
            ]
        ]);

        $response->assertJsonFragment([
            'summary' => [
                'revenue' => 10000.0,
                'costs' => 4000.0,
                'gross_profit' => 6000.0,
                'expenses' => 1500.0,
                'taxes' => 0.0,
                'operating_profit' => 4500.0,
            ]
        ]);
    }
}
