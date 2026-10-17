<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TransactionExportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guests_are_redirected_to_the_login_page(): void
    {
        $this->get(route('transactions.export'))->assertRedirect(route('login'));
    }

    #[Test]
    public function a_user_downloads_their_own_transactions_as_csv(): void
    {
        $user = User::factory()->create();
        $groceries = Category::factory()->for($user)->create(['name' => 'Groceries', 'type' => 'Expense']);
        $salary = Category::factory()->for($user)->create(['name' => 'Salary', 'type' => 'Income']);

        Transaction::factory()->for($user)->for($groceries)->create([
            'type' => 'Expense',
            'amount' => 42.50,
            'transaction_date' => '2026-10-12',
            'description' => 'Coffee, milk, eggs',
        ]);
        Transaction::factory()->for($user)->for($salary)->create([
            'type' => 'Income',
            'amount' => 3200,
            'transaction_date' => '2026-10-01',
            'description' => 'October salary',
        ]);
        Transaction::factory()->create(['description' => 'Someone else entry']);

        $response = $this->actingAs($user)->get(route('transactions.export'));

        $response->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8')
            ->assertDownload('fortuna-transactions-'.now()->format('Y-m-d').'.csv');

        $csv = $response->streamedContent();

        $this->assertStringContainsString('Date,Type,Category,Description,"Amount (MYR)"', $csv);
        $this->assertStringContainsString('2026-10-12,Expense,Groceries,"Coffee, milk, eggs",42.50', $csv);
        $this->assertStringContainsString('2026-10-01,Income,Salary,"October salary",3200.00', $csv);
        $this->assertStringNotContainsString('Someone else entry', $csv);

        // Newest first, mirroring the index.
        $this->assertLessThan(strpos($csv, 'October salary'), strpos($csv, 'Coffee, milk, eggs'));
    }

    #[Test]
    public function the_export_respects_the_active_filters(): void
    {
        $user = User::factory()->create();
        $expense = Category::factory()->for($user)->create(['type' => 'Expense']);
        $income = Category::factory()->for($user)->create(['type' => 'Income']);

        Transaction::factory()->for($user)->for($expense)->create([
            'type' => 'Expense', 'amount' => 15, 'transaction_date' => '2026-10-01', 'description' => 'Early expense',
        ]);
        Transaction::factory()->for($user)->for($expense)->create([
            'type' => 'Expense', 'amount' => 20, 'transaction_date' => '2026-10-12', 'description' => 'Weekly shop',
        ]);
        Transaction::factory()->for($user)->for($income)->create([
            'type' => 'Income', 'amount' => 300, 'transaction_date' => '2026-10-12', 'description' => 'October salary',
        ]);

        $csv = $this->actingAs($user)
            ->get(route('transactions.export', ['type' => 'Expense', 'start_date' => '2026-10-05']))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('Weekly shop', $csv);
        $this->assertStringNotContainsString('Early expense', $csv);
        $this->assertStringNotContainsString('October salary', $csv);
    }

    #[Test]
    public function an_empty_type_filter_from_the_form_does_not_blank_the_export(): void
    {
        $user = User::factory()->create();
        $expense = Category::factory()->for($user)->create(['type' => 'Expense']);
        $income = Category::factory()->for($user)->create(['type' => 'Income']);

        Transaction::factory()->for($user)->for($expense)->create([
            'type' => 'Expense', 'transaction_date' => '2026-10-12', 'description' => 'Weekly shop',
        ]);
        Transaction::factory()->for($user)->for($income)->create([
            'type' => 'Income', 'transaction_date' => '2026-10-12', 'description' => 'October salary',
        ]);

        $csv = $this->actingAs($user)
            ->get(route('transactions.export', ['type' => '', 'start_date' => '', 'end_date' => '']))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('Weekly shop', $csv);
        $this->assertStringContainsString('October salary', $csv);
    }

    #[Test]
    public function the_export_carries_the_search_and_the_chosen_order(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);

        Transaction::factory()->for($user)->for($category)->create([
            'amount' => 10, 'transaction_date' => '2026-10-01', 'description' => 'Tea and coffee',
        ]);
        Transaction::factory()->for($user)->for($category)->create([
            'amount' => 90, 'transaction_date' => '2026-10-20', 'description' => 'Train season pass',
        ]);

        $searched = $this->actingAs($user)
            ->get(route('transactions.export', ['search' => 'coffee']))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('Tea and coffee', $searched);
        $this->assertStringNotContainsString('Train season pass', $searched);

        $ordered = $this->actingAs($user)
            ->get(route('transactions.export', ['sort' => 'oldest']))
            ->assertOk()
            ->streamedContent();

        $this->assertLessThan(strpos($ordered, 'Train season pass'), strpos($ordered, 'Tea and coffee'));
    }
}
