<?php

namespace Tests\Feature;

use App\Enums\ProductType;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\DigitalProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminProductAndPaymentMethodTest extends TestCase
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

    public function test_admin_can_view_product_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Produk Digital');
    }

    public function test_customer_cannot_access_admin_products(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.products.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_game_product_with_variants(): void
    {
        $category = Category::where('slug', 'topup-game')->first();

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Valorant Point',
            'type' => 'game',
            'publisher' => 'Riot Games',
            'description' => 'Top Up Valorant Points Murah',
            'instructions' => 'Masukkan Riot ID dan Tagline',
            'requires_user_id' => 1,
            'user_id_label' => 'Riot ID',
            'requires_zone_id' => 0,
            'variants' => [
                [
                    'name' => '475 VP',
                    'sku' => 'VAL-475',
                    'price' => 55000,
                    'cost_price' => 50000,
                    'stock_type' => 'unlimited',
                ],
                [
                    'name' => '1000 VP',
                    'sku' => 'VAL-1000',
                    'price' => 110000,
                    'cost_price' => 102000,
                    'stock_type' => 'unlimited',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Valorant Point',
            'publisher' => 'Riot Games',
        ]);
        $this->assertDatabaseHas('product_variants', [
            'sku' => 'VAL-475',
            'price' => 55000,
        ]);
    }

    public function test_admin_can_create_account_product_with_credentials_stock(): void
    {
        $category = Category::where('slug', 'akun-premium')->first();

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Canva Pro 1 Tahun',
            'type' => 'account',
            'publisher' => 'Canva Pty',
            'requires_user_id' => 0,
            'requires_zone_id' => 0,
            'variants' => [
                [
                    'name' => '1 Tahun Private Invite',
                    'sku' => 'CANVA-1Y',
                    'price' => 25000,
                    'cost_price' => 10000,
                    'stock_type' => 'auto_stock',
                    'credentials' => "Email: canva1@pro.com | Pass: Canva#123\n---\nEmail: canva2@pro.com | Pass: Canva#456",
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Canva Pro 1 Tahun']);
        $this->assertDatabaseHas('account_stocks', ['credentials' => 'Email: canva1@pro.com | Pass: Canva#123']);
        $this->assertDatabaseHas('account_stocks', ['credentials' => 'Email: canva2@pro.com | Pass: Canva#456']);
    }

    public function test_admin_can_create_and_toggle_payment_method(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.payment-methods.store'), [
            'code' => 'GOPAY',
            'name' => 'GoPay E-Wallet',
            'group' => 'E-Wallet',
            'account_number' => '081234567890',
            'account_holder' => 'Admin GoPay',
            'fee_flat' => 1000,
            'fee_percent' => 1.5,
            'sort_order' => 10,
        ]);

        $response->assertRedirect(route('admin.payment-methods.index'));
        $this->assertDatabaseHas('payment_methods', [
            'code' => 'GOPAY',
            'name' => 'GoPay E-Wallet',
            'account_number' => '081234567890',
            'account_holder' => 'Admin GoPay',
            'is_active' => true,
        ]);

        $method = PaymentMethod::where('code', 'GOPAY')->first();

        // Toggle to inactive
        $toggleResponse = $this->actingAs($this->admin)->patch(route('admin.payment-methods.toggle', $method->id));
        $toggleResponse->assertRedirect();
        $this->assertDatabaseHas('payment_methods', [
            'code' => 'GOPAY',
            'is_active' => false,
        ]);
    }

    public function test_admin_can_soft_delete_product_with_order_history(): void
    {
        $variant = ProductVariant::where('sku', 'ML-86')->first();
        $product = $variant->product;

        // Place an order containing this product
        $response = $this->postJson(route('orders.store'), [
            'variant_id' => $variant->id,
            'payment_method' => 'BCA',
            'customer_name' => 'Gamer Sejati',
            'customer_email' => 'gamer@gmail.com',
            'customer_phone' => '08123456789',
            'target_user_id' => '12345678',
            'target_zone_id' => '2024',
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('order_items', ['product_id' => $product->id]);

        // Attempt deletion by admin
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));
        $deleteResponse->assertRedirect(route('admin.products.index'));

        // Assert product and its variants are soft deleted without FK constraint error
        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertSoftDeleted('product_variants', ['id' => $variant->id]);

        // Verify order item still has its relations accessible via withTrashed
        $orderItem = OrderItem::where('product_id', $product->id)->first();
        $this->assertNotNull($orderItem);
        $this->assertNotNull($orderItem->product);
        $this->assertEquals($product->name, $orderItem->product->name);
        $this->assertNotNull($orderItem->variant);

        // Verify the product is hidden from active index
        $indexResponse = $this->actingAs($this->admin)->get(route('admin.products.index'));
        $indexResponse->assertStatus(200);
        $this->assertFalse($indexResponse->viewData('products')->pluck('id')->contains($product->id));
    }

    public function test_sku_from_deleted_product_or_variant_can_be_reused(): void
    {
        $category = Category::first();
        $originalSku = 'REUSE-TEST-SKU';

        // 1. Create product with SKU
        $createResponse = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Produk Awal',
            'type' => 'game',
            'variants' => [
                [
                    'name' => 'Paket Awal',
                    'sku' => $originalSku,
                    'price' => 50000,
                    'stock_type' => 'unlimited',
                ],
            ],
        ]);
        $createResponse->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Produk Awal')->first();
        $this->assertNotNull($product);

        // 2. Delete the product
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));
        $deleteResponse->assertRedirect(route('admin.products.index'));
        $this->assertSoftDeleted('products', ['id' => $product->id]);

        // 3. Reusing the SAME SKU on a new product must SUCCEED without validation or DB constraint error
        $reuseResponse = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Produk Baru Reused',
            'type' => 'game',
            'variants' => [
                [
                    'name' => 'Paket Baru',
                    'sku' => $originalSku,
                    'price' => 60000,
                    'stock_type' => 'unlimited',
                ],
            ],
        ]);
        $reuseResponse->assertRedirect(route('admin.products.index'));
        $reuseResponse->assertSessionHasNoErrors();

        $this->assertDatabaseHas('product_variants', [
            'sku' => $originalSku,
            'price' => 60000,
            'deleted_at' => null,
        ]);
    }

    public function test_legacy_soft_deleted_sku_is_automatically_freed_when_creating_new_variant(): void
    {
        $category = Category::where('slug', 'akun-premium')->first();
        $legacySku = 'LEGACY-SKU-999';

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Legacy Product',
            'slug' => 'legacy-product-test',
            'type' => 'account',
            'is_active' => true,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Legacy Variant',
            'sku' => $legacySku,
            'price' => 10000,
            'stock_type' => 'unlimited',
            'is_active' => true,
        ]);

        // Simulate legacy soft deletion in database without renaming SKU
        DB::table('product_variants')->where('id', $variant->id)->update(['deleted_at' => now()]);

        // Creating a new product with the same legacy SKU must succeed without DB constraint error
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'New Product Using Legacy Sku',
            'type' => 'account',
            'variants' => [
                [
                    'name' => 'New Variant',
                    'sku' => $legacySku,
                    'price' => 15000,
                    'stock_type' => 'unlimited',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHasNoErrors();

        // The legacy soft deleted variant was automatically renamed
        $oldVariant = DB::table('product_variants')->where('id', $variant->id)->first();
        $this->assertStringContainsString('-deleted-', $oldVariant->sku);

        // The active variant owns the original SKU
        $this->assertDatabaseHas('product_variants', [
            'sku' => $legacySku,
            'deleted_at' => null,
            'price' => 15000,
        ]);
    }

    public function test_admin_can_create_pulsa_and_electricity_product_with_variants(): void
    {
        $category = Category::create([
            'name' => 'Pulsa & Token Listrik',
            'slug' => 'pulsa-token-listrik',
            'type' => 'pulsa',
            'icon' => '⚡',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Token Listrik PLN Prabayar',
            'type' => 'pulsa',
            'publisher' => 'PLN',
            'requires_user_id' => 1,
            'user_id_label' => 'No. Meter / ID Pelanggan PLN',
            'requires_zone_id' => 0,
            'variants' => [
                [
                    'name' => 'Token PLN 20.000',
                    'sku' => 'PLN-20K',
                    'price' => 21500,
                    'cost_price' => 20000,
                    'stock_type' => 'unlimited',
                ],
                [
                    'name' => 'Token PLN 50.000',
                    'sku' => 'PLN-50K',
                    'price' => 51500,
                    'cost_price' => 50000,
                    'stock_type' => 'unlimited',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHasNoErrors();

        $product = Product::where('name', 'Token Listrik PLN Prabayar')->first();
        $this->assertNotNull($product);
        $this->assertTrue($product->isPulsa());
        $this->assertEquals(ProductType::Pulsa, $product->type);
        $this->assertEquals('No. Meter / ID Pelanggan PLN', $product->user_id_label);
        $this->assertCount(2, $product->variants);
    }

    public function test_admin_can_delete_photo_from_product_gallery(): void
    {
        $category = Category::first();
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Akun Game Test Gallery',
            'slug' => 'akun-game-test-gallery',
            'type' => ProductType::GameAccount,
            'thumbnail' => 'products/test_thumb.jpg',
            'gallery' => [
                'products/test_thumb.jpg',
                'products/detail_photo1.jpg',
                'products/detail_photo2.jpg',
            ],
            'is_active' => true,
        ]);

        $this->assertCount(3, $product->gallery);
        $this->assertCount(3, $product->getGalleryItems());

        // Delete photo at index 1 ('products/detail_photo1.jpg')
        $response = $this->actingAs($this->admin)->deleteJson(
            route('admin.products.gallery.destroy', ['product' => $product->id, 'index' => 1]),
            ['path' => 'products/detail_photo1.jpg']
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $product->refresh();
        $this->assertCount(2, $product->gallery);
        $this->assertEquals(['products/test_thumb.jpg', 'products/detail_photo2.jpg'], $product->gallery);
    }

    public function test_admin_can_delete_product_thumbnail(): void
    {
        $category = Category::first();
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Akun Game Test Thumb',
            'slug' => 'akun-game-test-thumb',
            'type' => ProductType::GameAccount,
            'thumbnail' => 'products/cover_to_delete.jpg',
            'gallery' => [
                'products/cover_to_delete.jpg',
                'products/next_photo.jpg',
            ],
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->deleteJson(
            route('admin.products.thumbnail.destroy', ['product' => $product->id])
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $product->refresh();
        // Since next_photo exists, it was promoted as thumbnail
        $this->assertEquals('products/next_photo.jpg', $product->thumbnail);
    }
}
