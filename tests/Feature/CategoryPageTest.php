<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guests_are_redirected_to_login(): void
    {
        $this->get(route('categories.index'))
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function the_page_lists_the_authenticated_users_categories(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['name' => 'Groceries']);
        Category::factory()->create(['name' => 'Someone else category']);

        $this->actingAs($user)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSee('Groceries')
            ->assertSee('New category')
            ->assertDontSee('Someone else category');
    }

    #[Test]
    public function a_user_can_create_a_category(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('categories.store'), [
                'name' => 'Dining Out',
                'type' => 'Expense',
                'color' => '#f53003',
            ])
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'Dining Out',
            'type' => 'Expense',
            'color' => '#f53003',
        ]);
    }

    #[Test]
    public function creation_fails_with_a_required_name(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('categories.index', ['edit' => 1]))
            ->post(route('categories.store'), ['name' => '', 'type' => 'Expense'])
            ->assertRedirect(route('categories.index', ['edit' => 1]))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('categories', 0);
    }

    #[Test]
    public function a_user_can_update_their_own_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['name' => 'Old Name']);

        $this->actingAs($user)
            ->put(route('categories.update', $category), [
                'name' => 'New Name',
                'type' => 'Income',
                'color' => '#36A2EB',
            ])
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
            'type' => 'Income',
            'color' => '#36A2EB',
        ]);
    }

    #[Test]
    public function a_user_cannot_update_another_users_category(): void
    {
        $category = Category::factory()->create(['name' => 'Not Mine']);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('categories.update', $category), [
                'name' => 'Hacked',
                'type' => 'Income',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Not Mine']);
    }

    #[Test]
    public function an_unused_category_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();

        $this->actingAs($user)
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    #[Test]
    public function a_category_in_use_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();
        Transaction::factory()->for($category)->for($category->user, 'user')->create();

        $this->actingAs($category->user)
            ->delete(route('categories.destroy', $category))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
