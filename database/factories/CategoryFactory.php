<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Income' => ['Housing', 'Salary', 'Freelancing', 'Investments', 'Gifts'],
            'Expense' => ['Food', 'Rent', 'Utilities', 'Entertainment', 'Transport', 'Health', 'Shopping'],
        ];

        $type = fake()->randomElement(array_keys($categories));
        $name = fake()->randomElement($categories[$type]);

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'type' => $type,
            'color' => '#'.str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
        ];
    }
}
