<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
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

    public function test_admin_can_export_orders_to_formatted_excel_and_csv(): void
    {
        $product = Product::first();
        $variant = ProductVariant::where('product_id', $product->id)->first();

        $order = Order::create([
            'order_number' => 'INV-TEST-FORMAT01',
            'customer_name' => 'Siti Aisyah',
            'customer_email' => 'siti@example.com',
            'customer_phone' => '085150718231',
            'product_type' => $product->type,
            'subtotal' => 50000,
            'total_amount' => 50000,
            'payment_method' => 'BCA',
            'status' => OrderStatus::Completed,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'variant_name' => $variant->name,
            'price' => 50000,
            'quantity' => 1,
            'subtotal' => 50000,
        ]);

        // 1. Test Excel format (.xls)
        $excelResponse = $this->actingAs($this->admin)->get(route('admin.orders.export', ['format' => 'excel']));
        $excelResponse->assertStatus(200);
        $this->assertStringContainsString('.xls', $excelResponse->headers->get('content-disposition'));
        $excelContent = $excelResponse->streamedContent();
        $this->assertStringContainsString('LAPORAN REKAP TRANSAKSI', $excelContent);
        $this->assertStringContainsString('INV-TEST-FORMAT01', $excelContent);
        $this->assertStringContainsString('Siti Aisyah', $excelContent);

        // 2. Test CSV format (.csv)
        $csvResponse = $this->actingAs($this->admin)->get(route('admin.orders.export', ['format' => 'csv']));
        $csvResponse->assertStatus(200);
        $this->assertStringContainsString('.csv', $csvResponse->headers->get('content-disposition'));
        $csvContent = $csvResponse->streamedContent();
        $this->assertStringContainsString('INV-TEST-FORMAT01', $csvContent);
        $this->assertStringContainsString('Siti Aisyah', $csvContent);
    }

    public function test_admin_can_export_orders_filtered_by_product_and_size(): void
    {
        $product = Product::first();
        $variant = ProductVariant::where('product_id', $product->id)->first();

        // Order 1: Preorder Size XL
        $orderPreorder = Order::create([
            'order_number' => 'INV-PREORDER-XL',
            'customer_name' => 'Dimas Anggara',
            'customer_email' => 'dimas@example.com',
            'customer_phone' => '081234567891',
            'product_type' => ProductType::Preorder,
            'target_user_id' => 'XL',
            'target_zone_id' => 'Lengan Panjang',
            'target_nickname' => 'Dimas A.',
            'subtotal' => 150000,
            'total_amount' => 150000,
            'payment_method' => 'BCA',
            'status' => OrderStatus::Completed,
        ]);

        OrderItem::create([
            'order_id' => $orderPreorder->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => 'Kemeja PDH Himpunan',
            'variant_name' => 'Lengan Panjang',
            'price' => 150000,
            'quantity' => 1,
            'subtotal' => 150000,
        ]);

        // Order 2: Game Diamond Top Up
        $orderGame = Order::create([
            'order_number' => 'INV-DIAMOND-MLBB',
            'customer_name' => 'Reza Pratama',
            'customer_email' => 'reza@example.com',
            'customer_phone' => '081234567892',
            'product_type' => ProductType::Game,
            'target_user_id' => '987654321',
            'target_zone_id' => '2123',
            'target_nickname' => 'LemonPro',
            'subtotal' => 21000,
            'total_amount' => 21000,
            'payment_method' => 'QRIS',
            'status' => OrderStatus::Completed,
        ]);

        OrderItem::create([
            'order_id' => $orderGame->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => 'Mobile Legends: Bang Bang',
            'variant_name' => '86 Diamonds (78 + 8 Bonus)',
            'price' => 21000,
            'quantity' => 1,
            'subtotal' => 21000,
        ]);

        // Filter by size XL: should contain INV-PREORDER-XL but NOT INV-DIAMOND-MLBB
        $responseSize = $this->actingAs($this->admin)->get(route('admin.orders.export', [
            'clothing_size' => 'XL',
            'format' => 'csv',
        ]));

        $responseSize->assertStatus(200);
        $sizeContent = $responseSize->streamedContent();
        $this->assertStringContainsString('INV-PREORDER-XL', $sizeContent);
        $this->assertStringContainsString('Dimas Anggara', $sizeContent);
        $this->assertStringNotContainsString('INV-DIAMOND-MLBB', $sizeContent);

        // Filter by search keyword "LemonPro": should find Game order
        $responseSearch = $this->actingAs($this->admin)->get(route('admin.orders.export', [
            'search' => 'LemonPro',
            'format' => 'csv',
        ]));

        $responseSearch->assertStatus(200);
        $searchContent = $responseSearch->streamedContent();
        $this->assertStringContainsString('INV-DIAMOND-MLBB', $searchContent);
        $this->assertStringContainsString('987654321', $searchContent);
        $this->assertStringNotContainsString('INV-PREORDER-XL', $searchContent);
    }

    public function test_admin_orders_index_filters_work_properly(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', [
            'search' => 'NonExistentInvoice99999',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tidak ada data pesanan yang sesuai dengan filter.');
    }

    public function test_guest_and_customer_cannot_export_orders(): void
    {
        $guestResponse = $this->get(route('admin.orders.export'));
        $guestResponse->assertRedirect(route('login'));

        $customerResponse = $this->actingAs($this->customer)->get(route('admin.orders.export'));
        $customerResponse->assertStatus(403);
    }
}
