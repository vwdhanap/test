<?php

namespace Database\Factories;

use App\Models\CheckList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CheckList>
 */
class CheckListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => (User::inRandomOrder()->first())->id,
            'parent_id' => (CheckList::inRandomOrder()->first())->id,
            'name' => fake()->word(),
            'status' => fake()->boolean()
        ];
    }
}
