<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Fills the owner's account with a realistic ledger: a set of categories,
 * about two years of transactions, and the last few months of budgets.
 *
 * Run with: php artisan db:seed --class=MiraSeeder
 *
 * Safe to re-run: it seeds nothing once the account already has transactions.
 */
class MiraSeeder extends Seeder
{
    private const EMAIL = 'danialzamri0908@gmail.com';

    /** @var list<string> */
    private const FOOD = [
        "Groceries at Lotus's",
        'GrabFood dinner',
        'Kopitiam breakfast',
        'Jaya Grocer groceries',
        'Mixed rice lunch',
        'Bakery run',
        'Night market snacks',
    ];

    /** @var list<string> */
    private const TRANSPORT = [
        'Grab ride',
        'Petrol - Shell',
        "Touch 'n Go top-up",
        'Parking',
        'Car wash',
    ];

    /** @var list<string> */
    private const ENTERTAINMENT = [
        'Netflix subscription',
        'Spotify subscription',
        'Cinema tickets',
        'Steam game',
        'Board game night',
    ];

    /** @var list<string> */
    private const HEALTH = [
        'Pharmacy',
        'Clinic visit',
        'Dental check-up',
        'Vitamins',
    ];

    /** @var list<string> */
    private const SHOPPING = [
        'Shopee order',
        'Uniqlo',
        'Lazada order',
        'New shoes',
        'Home supplies',
    ];

    public function run(): void
    {
        $user = User::where('email', self::EMAIL)->first();

        if (! $user) {
            $this->command->warn('No account found for '.self::EMAIL.' - nothing seeded.');

            return;
        }

        if (Transaction::where('user_id', $user->id)->exists()) {
            $this->command->warn($user->name.' already has transactions - nothing seeded.');

            return;
        }

        $categories = $this->seedCategories($user);
        $transactions = $this->seedTransactions($user, $categories);
        $budgets = $this->seedBudgets($user, $categories);

        $this->command->info(sprintf(
            'Seeded %d categories, %d transactions, and %d budgets for %s.',
            $categories->count(),
            $transactions,
            $budgets,
            $user->name
        ));
    }

    /**
     * @return Collection<string, Category>
     */
    private function seedCategories(User $user): Collection
    {
        $definitions = [
            ['name' => 'Salary', 'type' => 'Income', 'color' => '#15803D'],
            ['name' => 'Freelancing', 'type' => 'Income', 'color' => '#0E7490'],
            ['name' => 'Rent', 'type' => 'Expense', 'color' => '#94A3B8'],
            ['name' => 'Food', 'type' => 'Expense', 'color' => '#2563EB'],
            ['name' => 'Utilities', 'type' => 'Expense', 'color' => '#7C3AED'],
            ['name' => 'Transport', 'type' => 'Expense', 'color' => '#EA580C'],
            ['name' => 'Entertainment', 'type' => 'Expense', 'color' => '#DB2777'],
            ['name' => 'Health', 'type' => 'Expense', 'color' => '#DC2626'],
            ['name' => 'Shopping', 'type' => 'Expense', 'color' => '#CA8A04'],
        ];

        $categories = collect();

        foreach ($definitions as $definition) {
            $categories->put($definition['name'], Category::create([
                'user_id' => $user->id,
                ...$definition,
            ]));
        }

        return $categories;
    }

    private function seedTransactions(User $user, Collection $categories): int
    {
        $today = CarbonImmutable::now()->startOfDay();
        $rows = [];

        for ($month = CarbonImmutable::create(2025, 1, 1); $month->lessThanOrEqualTo($today); $month = $month->addMonth()) {
            $this->monthRows($rows, $user, $categories, $month, $today);
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            Transaction::insert($chunk);
        }

        return count($rows);
    }

    /**
     * The ledger rows for one month: fixed bills, a salary, and everyday spending.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @param  Collection<string, Category>  $categories
     */
    private function monthRows(array &$rows, User $user, Collection $categories, CarbonImmutable $month, CarbonImmutable $today): void
    {
        // Salary lands on the 28th, rent on the 1st, utilities on the 5th.
        $this->pushOnDay($rows, $user, $categories['Salary'], $month, 28, 5800 + random_int(0, 8) * 50, 'Monthly salary', $today);
        $this->pushOnDay($rows, $user, $categories['Rent'], $month, 1, $month->year >= 2026 ? 1500 : 1450, 'Monthly rent', $today);
        $this->pushOnDay($rows, $user, $categories['Utilities'], $month, 5, random_int(16500, 27500) / 100, 'Utilities bill', $today);

        if (random_int(1, 100) <= 35) {
            $this->pushOnDay($rows, $user, $categories['Freelancing'], $month, random_int(5, 25), random_int(60000, 240000) / 100, 'Freelance project', $today);
        }

        $this->pushSpending($rows, $user, $categories['Food'], $month, random_int(5, 7), 1800, 16500, self::FOOD, $today);
        $this->pushSpending($rows, $user, $categories['Transport'], $month, random_int(2, 4), 600, 12000, self::TRANSPORT, $today);
        $this->pushSpending($rows, $user, $categories['Entertainment'], $month, random_int(1, 2), 2500, 14000, self::ENTERTAINMENT, $today);
        $this->pushSpending($rows, $user, $categories['Shopping'], $month, random_int(1, 2), 4500, 45000, self::SHOPPING, $today);

        if (random_int(0, 1) === 1) {
            $this->pushSpending($rows, $user, $categories['Health'], $month, 1, 4000, 32000, self::HEALTH, $today);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  list<string>  $descriptions
     */
    private function pushSpending(array &$rows, User $user, Category $category, CarbonImmutable $month, int $count, int $minCents, int $maxCents, array $descriptions, CarbonImmutable $today): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->pushOnDay($rows, $user, $category, $month, random_int(2, 28), random_int($minCents, $maxCents) / 100, $descriptions[array_rand($descriptions)], $today);
        }
    }

    /**
     * Append one row on a day of the month, skipping dates that are still in the future.
     *
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function pushOnDay(array &$rows, User $user, Category $category, CarbonImmutable $month, int $day, float $amount, string $description, CarbonImmutable $today): void
    {
        $date = $month->setDay(min($day, $month->daysInMonth));

        if ($date->greaterThan($today)) {
            return;
        }

        $timestamp = $date->setTime(random_int(8, 22), random_int(0, 59));

        $rows[] = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => $amount,
            'type' => $category->type,
            'transaction_date' => $date->toDateString(),
            'description' => $description,
            'receipt_path' => null,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
    }

    private function seedBudgets(User $user, Collection $categories): int
    {
        $amounts = [
            'Rent' => 1500,
            'Food' => 550,
            'Utilities' => 250,
            'Transport' => 250,
            'Entertainment' => 150,
            'Health' => 150,
            'Shopping' => 200,
        ];

        $months = [
            now()->subMonthsNoOverflow(2)->format('Y-m'),
            now()->subMonthNoOverflow()->format('Y-m'),
            now()->format('Y-m'),
        ];

        $created = 0;

        foreach ($amounts as $name => $amount) {
            foreach ($months as $month) {
                Budget::create([
                    'user_id' => $user->id,
                    'category_id' => $categories[$name]->id,
                    'month' => $month,
                    'amount' => $amount,
                ]);

                $created++;
            }
        }

        return $created;
    }
}
