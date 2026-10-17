<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MonthlySummaryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guests_are_redirected_to_the_login_page(): void
    {
        $this->get(route('reports.monthly-summary'))->assertRedirect(route('login'));
    }

    #[Test]
    public function a_user_downloads_this_months_summary_as_a_pdf(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);
        Budget::factory()->for($user)->for($category)->create(['month' => now()->format('Y-m'), 'amount' => 100]);
        Transaction::factory()->for($user)->for($category)->create([
            'type' => 'Expense', 'amount' => 25, 'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->get(route('reports.monthly-summary'));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertDownload('fortuna-summary-'.now()->format('Y-m').'.pdf');

        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    #[Test]
    public function the_summary_document_renders_the_figures_with_the_overshoot_flagged(): void
    {
        $overBudget = new Budget(['amount' => 100]);
        $overBudget->spent = 125;
        $overBudget->setRelation('category', new Category(['name' => 'Dining']));

        $withinBudget = new Budget(['amount' => 400]);
        $withinBudget->spent = 120;
        $withinBudget->setRelation('category', new Category(['name' => 'Rent']));

        $this->view('reports.monthly-summary', [
            'userName' => 'Mira',
            'monthName' => 'October 2026',
            'monthOnly' => 'October',
            'generatedAt' => now(),
            'entryCount' => 12,
            'income' => 3200,
            'spend' => 245,
            'net' => 2955,
            'budgetRemaining' => 255,
            'budgets' => collect([$overBudget, $withinBudget]),
            'topCategories' => collect([
                ['name' => 'Dining', 'amount' => 125.0, 'share' => 51.0],
                ['name' => 'Rent', 'amount' => 120.0, 'share' => 49.0],
            ]),
        ])
            ->assertSeeText('October 2026')
            ->assertSeeText('Prepared for Mira')
            ->assertSeeText('12 entries recorded')
            ->assertSeeText('MYR 3,200.00')
            ->assertSeeText('Past the line by MYR 25.00.')
            ->assertSeeText('125%')
            ->assertSeeText('51%');
    }

    #[Test]
    public function the_summary_document_handles_an_empty_month(): void
    {
        $this->view('reports.monthly-summary', [
            'userName' => 'Mira',
            'monthName' => 'October 2026',
            'monthOnly' => 'October',
            'generatedAt' => now(),
            'entryCount' => 0,
            'income' => 0,
            'spend' => 0,
            'net' => 0,
            'budgetRemaining' => null,
            'budgets' => collect(),
            'topCategories' => collect(),
        ])
            ->assertSeeText('Prepared for Mira')
            ->assertSeeText('No budgets set for October 2026.')
            ->assertSeeText('No budgets set for October.')
            ->assertSeeText('Nothing recorded as spent in October.');
    }
}
