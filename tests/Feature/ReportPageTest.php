<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReportPageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guests_are_redirected_to_login(): void
    {
        $this->get(route('reports.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function the_year_figures_months_and_comparison_render(): void
    {
        $user = User::factory()->create();
        $year = (int) now()->format('Y');
        $priorYear = $year - 1;

        $salary = Category::factory()->for($user)->create(['type' => 'Income', 'name' => 'Salary']);
        $rent = Category::factory()->for($user)->create(['type' => 'Expense', 'name' => 'Rent']);

        Transaction::factory()->for($user)->for($salary)->create(['amount' => 1200, 'type' => 'Income', 'transaction_date' => "{$year}-03-05"]);
        Transaction::factory()->for($user)->for($rent)->create(['amount' => 480, 'type' => 'Expense', 'transaction_date' => "{$year}-03-12"]);
        Transaction::factory()->for($user)->for($salary)->create(['amount' => 800, 'type' => 'Income', 'transaction_date' => "{$year}-05-20"]);
        Transaction::factory()->for($user)->for($rent)->create(['amount' => 300, 'type' => 'Expense', 'transaction_date' => "{$year}-05-25"]);
        Transaction::factory()->for($user)->for($salary)->create(['amount' => 500, 'type' => 'Income', 'transaction_date' => "{$priorYear}-06-01"]);

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSeeText("The year {$year}, month by month, in one register.")
            ->assertSee('<option value="'.$year.'" selected>', false)
            ->assertSee('<option value="'.$priorYear.'"', false)
            // The year's four answers.
            ->assertSee('2,000.00')
            ->assertSee('780.00')
            ->assertSee('1,220.00')
            ->assertSee('61.0%')
            // March's row: income, spent, net, and rate.
            ->assertSeeText('income +1,200.00 · spent 480.00 · rate 60.0%')
            // The movement against the closest earlier year, only where figures exist (no prior spending).
            ->assertSeeText("Against {$priorYear}: income +300.0%, net +144.0%, rate -39.0 pt.");
    }

    #[Test]
    public function transactions_from_other_users_do_not_count(): void
    {
        $user = User::factory()->create();
        $year = (int) now()->format('Y');

        Transaction::factory()->for($user)->for(Category::factory()->for($user)->create(['type' => 'Income']))->create(['amount' => 100, 'type' => 'Income', 'transaction_date' => "{$year}-03-05"]);
        Transaction::factory()->create(['amount' => 9999.99, 'type' => 'Income', 'transaction_date' => "{$year}-03-05"]);

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSee('100.00')
            ->assertDontSee('9,999.99');
    }

    #[Test]
    public function an_unusable_year_falls_back_to_the_current_year(): void
    {
        $user = User::factory()->create();
        $year = (int) now()->format('Y');

        $this->actingAs($user)
            ->get(route('reports.index', ['year' => 'not-a-year']))
            ->assertOk()
            ->assertSeeText("The year {$year}, month by month, in one register.");
    }

    #[Test]
    public function a_year_without_entries_shows_honest_empty_states(): void
    {
        $user = User::factory()->create();
        $year = (int) now()->format('Y');
        $emptyYear = $year + 3;

        $salary = Category::factory()->for($user)->create(['type' => 'Income']);
        Transaction::factory()->for($user)->for($salary)->create(['amount' => 100, 'type' => 'Income', 'transaction_date' => "{$year}-03-05"]);

        $this->actingAs($user)
            ->get(route('reports.index', ['year' => $emptyYear]))
            ->assertOk()
            ->assertSeeText("Nothing recorded in {$emptyYear} yet. The first entry you file draws the year here, month by month.")
            ->assertDontSee('year-flow-chart')
            ->assertDontSee('Loading the chart')
            ->assertSeeText("No spending recorded in {$emptyYear} yet.")
            ->assertSeeText("No income recorded in {$emptyYear} yet.");
    }

    #[Test]
    public function the_savings_rate_card_shows_an_em_dash_without_income(): void
    {
        $user = User::factory()->create();
        $year = (int) now()->format('Y');

        $rent = Category::factory()->for($user)->create(['type' => 'Expense']);
        Transaction::factory()->for($user)->for($rent)->create(['amount' => 300, 'type' => 'Expense', 'transaction_date' => "{$year}-03-05"]);

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSeeText("No income to rate in {$year} yet.")
            ->assertSee('-300.00');
    }

    #[Test]
    public function month_and_year_rows_link_to_their_detail(): void
    {
        $user = User::factory()->create();
        $year = (int) now()->format('Y');
        $priorYear = $year - 1;

        $salary = Category::factory()->for($user)->create(['type' => 'Income']);
        $rent = Category::factory()->for($user)->create(['type' => 'Expense']);
        Transaction::factory()->for($user)->for($salary)->create(['amount' => 100, 'type' => 'Income', 'transaction_date' => "{$year}-03-05"]);
        Transaction::factory()->for($user)->for($rent)->create(['amount' => 40, 'type' => 'Expense', 'transaction_date' => "{$year}-03-06"]);
        Transaction::factory()->for($user)->for($salary)->create(['amount' => 100, 'type' => 'Income', 'transaction_date' => "{$priorYear}-03-05"]);

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertOk()
            // March opens its entries in the transaction list.
            ->assertSee(route('transactions.index', ['start_date' => "{$year}-03-01", 'end_date' => "{$year}-03-31"]))
            // December still closes on its last day.
            ->assertSee(route('transactions.index', ['start_date' => "{$year}-12-01", 'end_date' => "{$year}-12-31"]))
            // The category sheets drill into the year's spending and income.
            ->assertSee(route('transactions.index', ['type' => 'Expense', 'start_date' => "{$year}-01-01", 'end_date' => "{$year}-12-31"]))
            ->assertSee(route('transactions.index', ['type' => 'Income', 'start_date' => "{$year}-01-01", 'end_date' => "{$year}-12-31"]))
            // An earlier year opens its own report.
            ->assertSee(route('reports.index', ['year' => $priorYear]));
    }

    #[Test]
    public function the_comparison_foot_says_so_when_no_earlier_year_exists(): void
    {
        $user = User::factory()->create();
        $year = (int) now()->format('Y');

        $salary = Category::factory()->for($user)->create(['type' => 'Income']);
        Transaction::factory()->for($user)->for($salary)->create(['amount' => 100, 'type' => 'Income', 'transaction_date' => "{$year}-03-05"]);

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSeeText('No earlier year in the register to compare with. Amounts in MYR.');
    }
}
