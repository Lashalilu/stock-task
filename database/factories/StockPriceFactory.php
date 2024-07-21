<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockPrice>
 */
class StockPriceFactory extends Factory
{
    protected $model = \App\Models\StockPrice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stock_id' => Stock::factory(), // Automatically create a Stock model
            'price' => $this->faker->randomFloat(2, 100, 1000), // Random price between 100 and 1000
            'created_at' => $this->faker->dateTimeThisYear(), // Random date within the current year
            'updated_at' => $this->faker->dateTimeThisYear(), // Random date within the current year
        ];
    }
}
