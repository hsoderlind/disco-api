<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
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
            'noteable_type' => Customer::class,
            'noteable_id' => 1,
            'title' => $this->faker->realText(24),
            'content' => $this->faker->realText(),
        ];
    }
}
