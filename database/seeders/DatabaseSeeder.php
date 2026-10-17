<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a primary user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a set of default categories for the user
        $categories = [
            ['name' => 'Salary', 'type' => 'Income'],
            ['name' => 'Freelancing', 'type' => 'Income'],
            ['name' => 'Food', 'type' => 'Expense'],
            ['name' => 'Rent', 'type' => 'Expense'],
            ['name' => 'Utilities', 'type' => 'Expense'],
            ['name' => 'Entertainment', 'type' => 'Expense'],
            ['name' => 'Transport', 'type' => 'Expense'],
            ['name' => 'Health', 'type' => 'Expense'],
            ['name' => 'Shopping', 'type' => 'Expense'],
        ];

        foreach ($categories as $catData) {
            $category = Category::create([
                'user_id' => $user->id,
                'name' => $catData['name'],
                'type' => $catData['type'],
                'color' => '#'.str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
            ]);

            // Create some transactions for each category
            Transaction::factory(rand(5, 15))->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'type' => $catData['type'],
            ]);

            // Create a monthly budget for each expense category
            if ($catData['type'] === 'Expense') {
                Budget::factory(3)->create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                ]);
            }
        }

        // Create some additional random users and data
        User::factory(5)->create()->each(function (User $u) {
            $userCats = Category::factory(5)->create(['user_id' => $u->id]);

            foreach ($userCats as $cat) {
                Transaction::factory(rand(10, 30))->create([
                    'user_id' => $u->id,
                    'category_id' => $cat->id,
                    'type' => $cat->type,
                ]);

                if ($cat->type === 'Expense') {
                    Budget::factory(3)->create([
                        'user_id' => $u->id,
                        'category_id' => $cat->id,
                    ]);
                }
            }
        });
    }
}
