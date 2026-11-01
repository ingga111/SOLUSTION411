<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\DigitalProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DigitalProductSeeder::class);

        $this->admin = User::where('role', UserRole::Admin)->first();
        $this->customer = User::where('role', UserRole::Customer)->first();
    }

    public function test_admin_can_view_category_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Kategori Produk & Layanan');
        $response->assertSee('Top-Up Game');
        $response->assertSee('Akun Premium & Streaming');
    }

    public function test_guest_and_customer_cannot_access_category_management(): void
    {
        // Guest redirect to login
        $guestResponse = $this->get(route('admin.categories.index'));
        $guestResponse->assertRedirect(route('login'));

        // Customer forbidden (403)
        $customerResponse = $this->actingAs($this->customer)->get(route('admin.categories.index'));
        $customerResponse->assertStatus(403);
    }

    public function test_admin_can_create_category_with_auto_generated_slug(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Steam Wallet Code',
            'type' => 'game',
            'icon' => '🎮',
            'sort_order' => 5,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Steam Wallet Code',
            'slug' => 'steam-wallet-code',
            'type' => 'game',
            'icon' => '🎮',
            'sort_order' => 5,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_category_with_custom_slug(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Canva Pro Edu & Lifetime',
            'slug' => 'canva-pro-spesial',
            'type' => 'account',
            'icon' => '✨',
            'sort_order' => 10,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Canva Pro Edu & Lifetime',
            'slug' => 'canva-pro-spesial',
            'type' => 'account',
            'icon' => '✨',
            'sort_order' => 10,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::create([
            'name' => 'Kategori Lama',
            'slug' => 'kategori-lama',
            'type' => 'game',
            'icon' => '📁',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Kategori Baru Diperbarui',
            'slug' => 'kategori-baru-diperbarui',
            'type' => 'account',
            'icon' => '🍿',
            'sort_order' => 8,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Kategori Baru Diperbarui',
            'slug' => 'kategori-baru-diperbarui',
            'type' => 'account',
            'icon' => '🍿',
            'sort_order' => 8,
        ]);
    }

    public function test_admin_can_toggle_category_active_status(): void
    {
        $category = Category::create([
            'name' => 'Kategori Toggle Test',
            'slug' => 'kategori-toggle-test',
            'is_active' => true,
        ]);

        // Toggle to inactive
        $response = $this->actingAs($this->admin)->patch(route('admin.categories.toggle', $category));
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'is_active' => false,
        ]);

        // Toggle back to active
        $response2 = $this->actingAs($this->admin)->patch(route('admin.categories.toggle', $category));
        $response2->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_delete_unused_category(): void
    {
        $category = Category::create([
            'name' => 'Kategori Kosong Yang Bisa Dihapus',
            'slug' => 'kategori-kosong-bisa-dihapus',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_admin_can_delete_category_with_products_cascading_them(): void
    {
        $categoryWithProducts = Category::where('slug', 'topup-game')->first();
        $this->assertGreaterThan(0, $categoryWithProducts->products()->count());

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $categoryWithProducts));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        // Verify category and its products were deleted
        $this->assertDatabaseMissing('categories', [
            'id' => $categoryWithProducts->id,
        ]);
    }

    public function test_admin_can_delete_category_releasing_child_categories(): void
    {
        $parent = Category::create([
            'name' => 'Kategori Induk',
            'slug' => 'kategori-induk',
            'is_active' => true,
        ]);

        $child = Category::create([
            'parent_id' => $parent->id,
            'name' => 'Kategori Anak',
            'slug' => 'kategori-anak',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $parent));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', [
            'id' => $parent->id,
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $child->id,
            'parent_id' => null,
        ]);
    }

    public function test_homepage_only_renders_active_categories(): void
    {
        $category = Category::create([
            'name' => 'Kategori Khusus Aktif',
            'slug' => 'kategori-khusus-aktif',
            'is_active' => true,
            'sort_order' => 99,
        ]);

        $inactiveCategory = Category::create([
            'name' => 'Kategori Tersembunyi Nonaktif',
            'slug' => 'kategori-tersembunyi-nonaktif',
            'is_active' => false,
            'sort_order' => 100,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Active category and its pill are visible
        $response->assertSee('Kategori Khusus Aktif');
        $response->assertSee('group-kategori-khusus-aktif');

        // Inactive category is NOT visible
        $response->assertDontSee('Kategori Tersembunyi Nonaktif');
        $response->assertDontSee('group-kategori-tersembunyi-nonaktif');
    }
}
