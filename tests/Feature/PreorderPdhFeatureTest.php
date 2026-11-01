<?php

namespace Tests\Feature;

use App\Enums\ProductType;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\DigitalProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreorderPdhFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DigitalProductSeeder::class);
    }

    public function test_can_create_and_checkout_pdh_preorder_with_dp_and_specs(): void
    {
        $category = Category::create([
            'name' => 'PDH HIMATIKA',
            'slug' => 'pdh-himatika',
            'type' => 'preorder',
            'icon' => 'shirt',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Kemeja PDH HIMATIKA 2026',
            'slug' => 'kemeja-pdh-himatika-2026',
            'type' => ProductType::Preorder->value,
            'publisher' => 'Konveksi HIMATIKA',
            'description' => 'Preorder Kemeja PDH resmi bahan Nagata Drill.',
            'account_specs' => [
                'Bahan' => 'Nagata Drill Premium',
                'Bordir' => 'Bordir Komputer Presisi',
            ],
            'is_active' => true,
        ]);

        $dpVariant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'DP (Uang Muka 50%)',
            'sku' => 'PDH-DP-50',
            'price' => 75000,
            'original_price' => 75000,
            'stock' => 100,
            'is_active' => true,
        ]);

        $lunasVariant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Lunas (Pembayaran Penuh)',
            'sku' => 'PDH-LUNAS-100',
            'price' => 150000,
            'original_price' => 150000,
            'stock' => 100,
            'is_active' => true,
        ]);

        // 1. Validation test: Size and sleeve are required for preorder
        $invalidResponse = $this->postJson(route('orders.store'), [
            'variant_id' => $dpVariant->id,
            'payment_method' => 'BCA',
            'customer_name' => 'Budi Mahasiswa',
            'customer_email' => 'budi@himatika.id',
            'customer_phone' => '081298765432',
            // Missing target_user_id (size) & target_zone_id (sleeve)
        ]);
        $invalidResponse->assertStatus(422);
        $invalidResponse->assertJsonValidationErrors(['target_user_id', 'target_zone_id']);

        // 2. Successful DP Preorder Checkout
        $response = $this->postJson(route('orders.store'), [
            'variant_id' => $dpVariant->id,
            'payment_method' => 'BCA',
            'customer_name' => 'Budi Mahasiswa',
            'customer_email' => 'budi@himatika.id',
            'customer_phone' => '081298765432',
            'target_user_id' => 'XL',
            'target_zone_id' => 'Lengan Panjang',
            'target_nickname' => 'BUDI SANTOSO / 220101001',
            'notes' => 'Tolong bordir nama dicek double ya kak.',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $orderNumber = $response->json('order_number');
        $this->assertNotEmpty($orderNumber);

        $order = Order::where('order_number', $orderNumber)->first();
        $this->assertNotNull($order);
        $this->assertTrue($order->isPreorder());
        $this->assertEquals('XL', $order->clothing_size);
        $this->assertEquals('Lengan Panjang', $order->sleeve_type);
        $this->assertEquals('BUDI SANTOSO / 220101001', $order->embroidery_name);
        $this->assertEquals('Tolong bordir nama dicek double ya kak.', $order->notes);

        // 3. Customer invoice view renders preorder details
        $customerInvoiceResponse = $this->get(route('orders.show', $orderNumber));
        $customerInvoiceResponse->assertStatus(200);
        $customerInvoiceResponse->assertSee('Detail Preorder PDH');
        $customerInvoiceResponse->assertSee('XL');
        $customerInvoiceResponse->assertSee('Lengan Panjang');
        $customerInvoiceResponse->assertSee('BUDI SANTOSO / 220101001');

        // 4. Admin order view renders preorder details & WhatsApp draft
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $adminOrderResponse = $this->actingAs($admin)->get(route('admin.orders.show', $order));
        $adminOrderResponse->assertStatus(200);
        $adminOrderResponse->assertSee('Spesifikasi Baju PDH');
        $adminOrderResponse->assertSee('Konfirmasi Preorder via WhatsApp');
        $adminOrderResponse->assertSee('XL');
        $adminOrderResponse->assertSee('Lengan Panjang');
    }
}
