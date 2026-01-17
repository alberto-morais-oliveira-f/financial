<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Factories;

use Am2tec\Financial\Domain\Enums\CategoryType;
use Am2tec\Financial\Infrastructure\Persistence\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'code' => $this->faker->unique()->slug,
            'type' => $this->faker->randomElement(CategoryType::cases()),
            'is_active' => true,
            'description' => $this->faker->sentence,
        ];
    }
}
