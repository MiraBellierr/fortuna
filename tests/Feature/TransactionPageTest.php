<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TransactionPageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_page_lists_the_authenticated_users_transactions(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['name' => 'Groceries']);
        Transaction::factory()->for($user)->for($category)->create(['type' => 'Expense']);
        Transaction::factory()->create(['type' => 'Expense']);

        $this->actingAs($user)
            ->get(route('transactions.index'))
            ->assertOk();
    }

    #[Test]
    public function the_type_filter_narrows_the_records(): void
    {
        $user = User::factory()->create();
        $expense = Category::factory()->for($user)->create(['type' => 'Expense']);
        $income = Category::factory()->for($user)->create(['type' => 'Income']);

        Transaction::factory()->for($user)->for($expense)->create(['type' => 'Expense', 'description' => 'Groceries run']);
        Transaction::factory()->for($user)->for($income)->create(['type' => 'Income', 'description' => 'Salaried pay']);

        $this->actingAs($user)
            ->get(route('transactions.index', ['type' => 'Income']))
            ->assertOk()
            ->assertSeeText('Salaried pay')
            ->assertDontSeeText('Groceries run');
    }

    #[Test]
    public function the_date_range_filter_works_with_the_type_left_at_all(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);

        Transaction::factory()->for($user)->for($category)->create(['transaction_date' => '2026-10-12', 'description' => 'Visible record']);
        Transaction::factory()->for($user)->for($category)->create(['transaction_date' => '2026-11-02', 'description' => 'Later record']);

        // The filter form submits empty strings for "All types" and blank dates.
        $this->actingAs($user)
            ->get(route('transactions.index', ['type' => '', 'start_date' => '2026-10-01', 'end_date' => '2026-10-31']))
            ->assertOk()
            ->assertSeeText('1 record matching these filters.')
            ->assertSeeText('Visible record')
            ->assertDontSeeText('Later record');
    }

    #[Test]
    public function the_api_index_ignores_an_empty_type_filter(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        Transaction::factory()->for($user)->for($category)->create();
        Transaction::factory()->for($user)->for($category)->create();

        $this->actingAs($user)
            ->getJson('/api/transactions?type=')
            ->assertOk()
            ->assertJsonCount(2);
    }

    #[Test]
    public function the_search_filter_matches_descriptions(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);

        Transaction::factory()->for($user)->for($category)->create(['description' => 'Weekly groceries']);
        Transaction::factory()->for($user)->for($category)->create(['description' => 'October electricity']);

        $this->actingAs($user)
            ->get(route('transactions.index', ['search' => 'grocer']))
            ->assertOk()
            ->assertSeeText('1 record matching "grocer".')
            ->assertSeeText('Weekly groceries')
            ->assertDontSeeText('October electricity');
    }

    #[Test]
    public function the_category_filter_narrows_to_one_category(): void
    {
        $user = User::factory()->create();
        $groceries = Category::factory()->for($user)->create(['name' => 'Groceries', 'type' => 'Expense']);
        $transport = Category::factory()->for($user)->create(['name' => 'Transport', 'type' => 'Expense']);

        Transaction::factory()->for($user)->for($groceries)->create(['description' => 'Market run']);
        Transaction::factory()->for($user)->for($transport)->create(['description' => 'Train pass']);

        $this->actingAs($user)
            ->get(route('transactions.index', ['category_id' => $groceries->id]))
            ->assertOk()
            ->assertSeeText('Market run')
            ->assertDontSeeText('Train pass');
    }

    #[Test]
    public function the_amount_range_filter_bounds_the_records(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);

        Transaction::factory()->for($user)->for($category)->create(['amount' => 5, 'description' => 'Small thing']);
        Transaction::factory()->for($user)->for($category)->create(['amount' => 25, 'description' => 'Mid thing']);
        Transaction::factory()->for($user)->for($category)->create(['amount' => 250, 'description' => 'Large thing']);

        $this->actingAs($user)
            ->get(route('transactions.index', ['amount_min' => 10, 'amount_max' => 100]))
            ->assertOk()
            ->assertSeeText('1 record matching these filters.')
            ->assertSeeText('Mid thing')
            ->assertDontSeeText('Small thing')
            ->assertDontSeeText('Large thing');
    }

    #[Test]
    public function the_advanced_filters_open_the_more_filters_disclosure(): void
    {
        $user = User::factory()->create();

        $withAdvanced = $this->actingAs($user)
            ->get(route('transactions.index', ['amount_min' => 10, 'end_date' => '2026-10-31']));

        $withAdvanced->assertOk()->assertSeeText('More filters · 2 active');
        $this->assertMatchesRegularExpression('/<details[^>]*\bopen\b/', $withAdvanced->getContent());

        $plain = $this->actingAs($user)->get(route('transactions.index'));

        $plain->assertOk()->assertSeeText('More filters')->assertDontSeeText('More filters ·');
        $this->assertDoesNotMatchRegularExpression('/<details[^>]*\bopen\b/', $plain->getContent());
    }

    #[Test]
    public function the_sort_options_reorder_the_records(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);

        Transaction::factory()->for($user)->for($category)->create([
            'transaction_date' => '2026-10-12', 'amount' => 25, 'description' => 'Older small record',
        ]);
        Transaction::factory()->for($user)->for($category)->create([
            'transaction_date' => '2026-11-02', 'amount' => 250, 'description' => 'Newer large record',
        ]);

        $this->actingAs($user)
            ->get(route('transactions.index', ['sort' => 'oldest']))
            ->assertOk()
            ->assertSeeTextInOrder(['Older small record', 'Newer large record']);

        $this->actingAs($user)
            ->get(route('transactions.index', ['sort' => 'highest']))
            ->assertOk()
            ->assertSeeTextInOrder(['Newer large record', 'Older small record']);

        // The default order stays newest first.
        $this->actingAs($user)
            ->get(route('transactions.index'))
            ->assertOk()
            ->assertSeeTextInOrder(['Newer large record', 'Older small record']);
    }

    #[Test]
    public function a_user_can_create_a_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['type' => 'Expense']);

        $this->actingAs($user)
            ->post(route('transactions.store'), [
                'category_id' => $category->id,
                'amount' => 42.50,
                'type' => 'Expense',
                'transaction_date' => '2026-10-15',
                'description' => 'Weekly groceries',
            ])
            ->assertRedirect(route('transactions.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 42.5,
            'type' => 'Expense',
            'description' => 'Weekly groceries',
        ]);
    }

    #[Test]
    public function creation_fails_with_an_invalid_month_or_missing_category(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('transactions.store'), [
                'amount' => 10,
                'type' => 'Expense',
                'transaction_date' => '2026-10-15',
            ])
            ->assertSessionHasErrors('category_id');

        $this->assertDatabaseCount('transactions', 0);
    }

    #[Test]
    public function a_transaction_cannot_use_another_users_category(): void
    {
        $otherCategory = Category::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('transactions.store'), [
                'category_id' => $otherCategory->id,
                'amount' => 10,
                'type' => 'Expense',
                'transaction_date' => '2026-10-15',
            ])
            ->assertSessionHasErrors('category_id');

        $this->assertDatabaseCount('transactions', 0);
    }

    #[Test]
    public function a_receipt_can_be_uploaded_with_the_transaction(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();

        $this->actingAs($user)
            ->post(route('transactions.store'), [
                'category_id' => $category->id,
                'amount' => 99.99,
                'type' => 'Expense',
                'transaction_date' => now()->format('Y-m-d'),
                'receipt' => UploadedFile::fake()->create('receipt.pdf', 50, 'application/pdf'),
            ])
            ->assertRedirect(route('transactions.index'))
            ->assertSessionHas('success');

        $transaction = Transaction::where('user_id', $user->id)->first();
        $this->assertNotNull($transaction);
        $this->assertMatchesRegularExpression('/^receipts\/user_\d+_/', $transaction->receipt_path);
        Storage::disk('public')->assertExists($transaction->receipt_path);
    }

    #[Test]
    public function guests_cannot_create_transactions(): void
    {
        $this->post(route('transactions.store'), ['amount' => 1])
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function a_user_can_update_their_own_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        $transaction = Transaction::factory()->for($user)->for($category)->create(['amount' => 10]);

        $this->actingAs($user)
            ->put(route('transactions.update', $transaction), [
                'category_id' => $category->id,
                'amount' => 77.77,
                'type' => 'Expense',
                'transaction_date' => '2026-10-16',
                'description' => 'Updated description',
            ])
            ->assertRedirect(route('transactions.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'amount' => 77.77,
            'description' => 'Updated description',
        ]);
    }

    #[Test]
    public function the_edit_modal_opens_for_a_transaction_being_edited(): void
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->for($user)->for(Category::factory()->for($user))->create(['amount' => 19]);

        $this->actingAs($user)
            ->get(route('transactions.index', ['edit' => $transaction->id]))
            ->assertOk()
            ->assertSee('Edit entry')
            ->assertSee('19.00');
    }

    #[Test]
    public function a_user_cannot_update_another_users_transaction(): void
    {
        $transaction = Transaction::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('transactions.update', $transaction), [
                'amount' => 999,
            ])
            ->assertForbidden();
    }

    #[Test]
    public function a_user_can_delete_their_own_transaction(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $transaction = Transaction::factory()->for($user)->for(Category::factory()->for($user))
            ->create(['receipt_path' => 'receipts/user_1_stored.png']);
        Storage::disk('public')->put($transaction->receipt_path, 'stub');

        $this->actingAs($user)
            ->delete(route('transactions.destroy', $transaction))
            ->assertRedirect(route('transactions.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
        Storage::disk('public')->assertMissing($transaction->receipt_path);
    }

    #[Test]
    public function a_user_cannot_delete_another_users_transaction(): void
    {
        $transaction = Transaction::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('transactions.destroy', $transaction))
            ->assertForbidden();

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
    }

    #[Test]
    public function a_user_can_bulk_delete_selected_transactions_with_their_receipts(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        $first = Transaction::factory()->for($user)->for($category)->create(['receipt_path' => 'receipts/user_1_first.png']);
        $second = Transaction::factory()->for($user)->for($category)->create();
        $kept = Transaction::factory()->for($user)->for($category)->create();

        Storage::disk('public')->put($first->receipt_path, 'stub');

        $this->actingAs($user)
            ->delete(route('transactions.bulk-destroy'), ['ids' => [$first->id, $second->id]])
            ->assertRedirect(route('transactions.index'))
            ->assertSessionHas('success', '2 transactions were deleted.');

        $this->assertDatabaseMissing('transactions', ['id' => $first->id]);
        $this->assertDatabaseMissing('transactions', ['id' => $second->id]);
        $this->assertDatabaseHas('transactions', ['id' => $kept->id]);
        Storage::disk('public')->assertMissing($first->receipt_path);
    }

    #[Test]
    public function a_bulk_delete_counts_each_selected_transaction_once_even_with_duplicated_ids(): void
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->for($user)->for(Category::factory()->for($user))->create();

        // The mobile list and the table mirror the same checkbox, so ids can arrive twice.
        $this->actingAs($user)
            ->delete(route('transactions.bulk-destroy'), ['ids' => [$transaction->id, $transaction->id]])
            ->assertRedirect(route('transactions.index'))
            ->assertSessionHas('success', '1 transaction was deleted.');

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    #[Test]
    public function a_bulk_delete_cannot_touch_another_users_transactions(): void
    {
        $user = User::factory()->create();
        $mine = Transaction::factory()->for($user)->for(Category::factory()->for($user))->create();
        $foreign = Transaction::factory()->create();

        $this->actingAs($user)
            ->delete(route('transactions.bulk-destroy'), ['ids' => [$mine->id, $foreign->id]])
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseMissing('transactions', ['id' => $mine->id]);
        $this->assertDatabaseHas('transactions', ['id' => $foreign->id]);
    }

    #[Test]
    public function a_bulk_delete_without_a_selection_reports_an_error(): void
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->for($user)->for(Category::factory()->for($user))->create();

        $this->actingAs($user)
            ->delete(route('transactions.bulk-destroy'), [])
            ->assertRedirect(route('transactions.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
    }

    #[Test]
    public function guests_cannot_bulk_delete_transactions(): void
    {
        $this->delete(route('transactions.bulk-destroy'), ['ids' => [1]])
            ->assertRedirect(route('login'));
    }
}
