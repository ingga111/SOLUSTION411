<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\DigitalProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardMetricsTest extends TestCase
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

    public function test_admin_can_view_dashboard_with_enhanced_metrics_and_chart_data(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas([
            'todayRevenue',
            'monthlyRevenue',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'totalCustomers',
            'availableAccountStocks',
            'recentOrders',
            'lowStockVariants',
            'salesChartLabels',
            'salesChartData',
        ]);

        $labels = $response->viewData('salesChartLabels');
        $data = $response->viewData('salesChartData');

        $this->assertCount(7, $labels);
        $this->assertCount(7, $data);
    }

    public function test_low_stock_variants_identifies_accounts_with_low_units(): void
    {
        $product = Product::first();

        // Create a variant with stock = 2 (low stock)
        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Akun Sultan Low Stock',
            'sku' => 'VAR-TEST-001',
            'price' => 50000,
            'stock_type' => 'account',
            'stock' => 2,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $lowStockVariants = $response->viewData('lowStockVariants');

        $this->assertNotEmpty($lowStockVariants);
        $this->assertTrue($lowStockVariants->contains('name', 'Akun Sultan Low Stock'));
    }

    public function test_guest_is_redirected_and_customer_forbidden_from_dashboard(): void
    {
        $guestResponse = $this->get(route('admin.dashboard'));
        $guestResponse->assertRedirect(route('login'));

        $customerResponse = $this->actingAs($this->customer)->get(route('admin.dashboard'));
        $customerResponse->assertStatus(403);
    }
}
