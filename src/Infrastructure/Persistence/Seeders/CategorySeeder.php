<?php

declare(strict_types=1);

namespace Am2tec\Financial\Infrastructure\Persistence\Seeders;

use Am2tec\Financial\Domain\Enums\CategoryType;
use Am2tec\Financial\Infrastructure\Persistence\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder cria um conjunto mínimo de categorias financeiras
     * para garantir que o sistema seja funcional desde o início.
     * Estas são categorias-raiz, agnósticas ao domínio de negócio.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Other Revenue',
                'code' => 'REV-001',
                'type' => CategoryType::REVENUE,
                'description' => 'Receitas não classificadas em outras categorias.'
            ],
            [
                'name' => 'Other Expenses',
                'code' => 'EXP-001',
                'type' => CategoryType::EXPENSE,
                'description' => 'Despesas operacionais não classificadas em outras categorias.'
            ],
            [
                'name' => 'Other Costs',
                'code' => 'CST-001',
                'type' => CategoryType::COST,
                'description' => 'Custos diretos não classificados em outras categorias.'
            ],
            [
                'name' => 'Other Taxes',
                'code' => 'TAX-001',
                'type' => CategoryType::TAX,
                'description' => 'Impostos não classificados em outras categorias.'
            ],
            [
                'name' => 'Other Assets',
                'code' => 'AST-001',
                'type' => CategoryType::ASSET,
                'description' => 'Ativos não classificados em outras categorias.'
            ],
            [
                'name' => 'Other Liabilities',
                'code' => 'LIA-001',
                'type' => CategoryType::LIABILITY,
                'description' => 'Passivos não classificados em outras categorias.'
            ],
            [
                'name' => 'Other Equity',
                'code' => 'EQT-001',
                'type' => CategoryType::EQUITY,
                'description' => 'Movimentações de patrimônio líquido não classificadas.'
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->firstOrCreate(
                ['code' => $category['code']],
                [
                    'uuid' => Str::uuid()->toString(),
                    'name' => $category['name'],
                    'type' => $category['type'],
                    'description' => $category['description'],
                    'is_active' => true,
                    'is_system_category' => true, // Marcadas como categorias de sistema
                ]
            );
        }
    }
}
