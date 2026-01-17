<?php

namespace Am2tec\Financial\Tests\Feature\Reports;

use Am2tec\Financial\Infrastructure\Persistence\Models\Category;
use Am2tec\Financial\Infrastructure\Persistence\Models\Entry;
use Am2tec\Financial\Infrastructure\Persistence\Models\Transaction;
use Am2tec\Financial\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DreReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Rodar migrações para criar as tabelas necessárias
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }

    /** @test */
    public function it_can_generate_a_dre_report()
    {
        // 1. Arrange
        // Criar categorias
        $revenueCategory = Category::factory()->create(['type' => 'REVENUE', 'name' => 'Sales']);
        $costCategory = Category::factory()->create(['type' => 'COST', 'name' => 'COGS']);
        $expenseCategory = Category::factory()->create(['type' => 'EXPENSE', 'name' => 'Rent']);

        // Criar transações e lançamentos
        $transaction1 = Transaction::factory()->create(['status' => 'POSTED', 'updated_at' => '2024-01-15 10:00:00']);
        Entry::factory()->create([
            'transaction_id' => $transaction1->id,
            'category_id' => $revenueCategory->id,
            'type' => 'credit',
            'amount' => 10000, // R$ 100,00
        ]);

        $transaction2 = Transaction::factory()->create(['status' => 'POSTED', 'updated_at' => '2024-01-16 11:00:00']);
        Entry::factory()->create([
            'transaction_id' => $transaction2->id,
            'category_id' => $costCategory->id,
            'type' => 'debit',
            'amount' => 4000, // R$ 40,00
        ]);

        $transaction3 = Transaction::factory()->create(['status' => 'POSTED', 'updated_at' => '2024-01-17 12:00:00']);
        Entry::factory()->create([
            'transaction_id' => $transaction3->id,
            'category_id' => $expenseCategory->id,
            'type' => 'debit',
            'amount' => 1500, // R$ 15,00
        ]);
        
        // Transação fora do período
        $transaction4 = Transaction::factory()->create(['status' => 'POSTED', 'updated_at' => '2024-02-01 10:00:00']);
        Entry::factory()->create([
            'transaction_id' => $transaction4->id,
            'category_id' => $revenueCategory->id,
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
                'revenue' => 10000,
                'costs' => 4000,
                'gross_profit' => 6000, // 10000 - 4000
                'expenses' => 1500,
                'taxes' => 0,
                'operating_profit' => 4500, // 6000 - 1500
            ]
        ]);
    }
}
