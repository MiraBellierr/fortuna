<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_create_a_budget_for_their_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);

        $this->actingAs($user)
            ->post('/api/budgets', [
                'category_id' => $category->id,
                'amount' => 250.50,
                'month' => '2026-10',
            ])
            ->assertCreated()
            ->assertJsonFragment(['amount' => 250.5]);

        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 250.5,
            'month' => '2026-10',
        ]);
    }

    #[Test]
    public function a_budget_cannot_be_created_with_another_users_category(): void
    {
        $otherCategory = Category::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post('/api/budgets', [
                'category_id' => $otherCategory->id,
                'amount' => 100,
                'month' => '2026-10',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('category_id');

        $this->assertDatabaseCount('budgets', 0);
    }

    #[Test]
    public function duplicate_budgets_for_the_same_category_and_month_are_rejected(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        Budget::factory()->for($user)->for($category)->create(['month' => '2026-10']);

        $this->actingAs($user)
            ->post('/api/budgets', [
                'category_id' => $category->id,
                'amount' => 500,
                'month' => '2026-10',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('budget');
    }

    #[Test]
    public function a_budget_can_be_created_from_the_web_page(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();

        $this->actingAs($user)
            ->post(route('budgets.store'), [
                'category_id' => $category->id,
                'amount' => 300,
                'month' => '2026-11',
            ])
            ->assertRedirect(route('budgets.index', ['month' => '2026-11']))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 300,
            'month' => '2026-11',
        ]);
    }

    #[Test]
    public function a_budget_can_be_updated_from_the_web_page(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        $budget = Budget::factory()->for($user)->for($category)->create(['month' => '2026-10', 'amount' => 100]);

        $this->actingAs($user)
            ->put(route('budgets.update', $budget), [
                'category_id' => $category->id,
                'amount' => 250,
                'month' => '2026-10',
            ])
            ->assertRedirect(route('budgets.index', ['month' => '2026-10']))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('budgets', ['id' => $budget->id, 'amount' => 250]);
    }

    #[Test]
    public function the_edit_modal_opens_for_a_budget_being_edited(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        $budget = Budget::factory()->for($user)->for($category)->create(['month' => '2026-10', 'amount' => 250]);

        $this->actingAs($user)
            ->get(route('budgets.index', ['edit' => $budget->id]))
            ->assertOk()
            ->assertSee('Edit budget')
            ->assertSee('250');
    }

    #[Test]
    public function a_user_cannot_update_another_users_budget_from_the_web_page(): void
    {
        $budget = Budget::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('budgets.update', $budget), [
                'category_id' => $budget->category_id,
                'amount' => 999,
                'month' => $budget->month,
            ])
            ->assertForbidden();
    }

    #[Test]
    public function the_limit_bar_shows_spending_within_the_limit(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);
        Budget::factory()->for($user)->for($category)->create(['month' => '2026-10', 'amount' => 100]);

        Transaction::factory()->for($user)->for($category)->create(['amount' => 40, 'type' => 'Expense', 'transaction_date' => '2026-10-05']);
        Transaction::factory()->for($user)->for($category)->create(['amount' => 30, 'type' => 'Expense', 'transaction_date' => '2026-10-12']);

        $this->actingAs($user)
            ->get(route('budgets.index'))
            ->assertOk()
            ->assertSeeText('70.00 / 100.00')
            ->assertDontSeeText('Over budget.');
    }

    #[Test]
    public function the_limit_bar_flags_over_budget_spending(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);
        Budget::factory()->for($user)->for($category)->create(['month' => '2026-10', 'amount' => 100]);

        Transaction::factory()->for($user)->for($category)->create(['amount' => 90, 'type' => 'Expense', 'transaction_date' => '2026-10-05']);
        Transaction::factory()->for($user)->for($category)->create(['amount' => 20, 'type' => 'Expense', 'transaction_date' => '2026-10-12']);

        $this->actingAs($user)
            ->get(route('budgets.index'))
            ->assertOk()
            ->assertSeeText('Over budget. 10.00 past the line.')
            ->assertSee('meter mt-2.5 meter-over');
    }

    #[Test]
    public function the_limit_bar_ignores_income_transactions_and_other_users(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);
        Budget::factory()->for($user)->for($category)->create(['month' => '2026-10', 'amount' => 100]);

        // Income transactions and other users' expenses must not count toward $user's spending
        Transaction::factory()->for($user)->for(Category::factory()->for($user)->create(['type' => 'Income']))->create(['amount' => 500, 'type' => 'Income', 'transaction_date' => '2026-10-05']);
        Transaction::factory()->create(['amount' => 999, 'type' => 'Expense', 'transaction_date' => '2026-10-05', 'category_id' => $category->id, 'user_id' => User::factory()->create()->id]);

        $this->actingAs($user)
            ->get(route('budgets.index'))
            ->assertOk()
            ->assertSeeText('0.00 / 100.00');
    }
}
