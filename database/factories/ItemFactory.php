<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryIds = \App\Models\Category::pluck('id')->toArray();
        $unitIds = \App\Models\Unit::pluck('id')->toArray();
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'category_id' => $this->faker->randomElement($categoryIds),
            'unit_id' => $this->faker->randomElement($unitIds),
            'is_shown_in_store' => $this->faker->boolean(),
            'minimum_stock' => $this->faker->randomFloat(2, 0, 20),
            'item_code' => $this->faker->randomNumber(5)
        ];
    }
}
