<?php

namespace Database\Factories;

use App\Services\Product\ProductCondition;
use App\Services\Product\ProductState;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        ShopSession::setId(1);

        return [
            'state' => ProductState::Draft,
            'price' => $this->faker->numberBetween(1, 2000),
            'cost_price' => $this->faker->randomNumber() * 10,
            'tax_id' => 1,
            'reference' => $this->faker->randomKey(),
            'condition' => ProductCondition::New,
            'name' => $this->faker->realText(32),
            'description' => $this->faker->realText(),
            'summary' => $this->faker->realText(),
        ];
    }
}
