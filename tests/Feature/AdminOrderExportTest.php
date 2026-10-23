<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\DigitalProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderExportTest extends TestCase
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

    public function test_admin_can_export_orders_to_excel_csv(): void
    {
        $product = Product::first();
        $variant = ProductVariant::where('product_id', $product->id)->first();

        // Create a test order
        $order = Order::create([
            'order_number' => 'INV-TEST-EXP001',
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '081234567890',
            'product_type' => $product->type,
            'subtotal' => 75000,
            'total_amount' => 75000,
            'payment_method' => 'QRIS',
            'status' => OrderStatus::Completed,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'variant_name' => $variant->name,
            'price' => 75000,
            'quantity' => 1,
            'subtotal' => 75000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.export'));

        $response->assertStatus(200);
        $this->assertTrue($response->headers->has('content-disposition'));
        $this->assertStringContainsString('laporan-pesanan-solustion411', $response->headers->get('content-disposition'));

        // Test stream content contains the order number and customer name
        $content = $response->streamedContent();
        $this->assertStringContainsString('INV-TEST-EXP001', $content);
        $this->assertStringContainsString('Budi Santoso', $content);
        $this->assertStringContainsString('75000', $content);
    }

    public function test_guest_and_customer_cannot_export_orders(): void
    {
        $guestResponse = $this->get(route('admin.orders.export'));
        $guestResponse->assertRedirect(route('login'));

        $customerResponse = $this->actingAs($this->customer)->get(route('admin.orders.export'));
        $customerResponse->assertStatus(403);
    }
}
