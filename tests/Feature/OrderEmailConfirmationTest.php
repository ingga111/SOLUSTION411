<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductType;
use App\Enums\UserRole;
use App\Mail\PaymentConfirmedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\DigitalProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderEmailConfirmationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DigitalProductSeeder::class);

        $this->admin = User::where('role', UserRole::Admin)->first();
    }

    public function test_admin_confirming_payment_triggers_automatic_email_to_customer(): void
    {
        Mail::fake();

        $product = Product::first();
        $variant = ProductVariant::where('product_id', $product->id)->first();

        $order = Order::create([
            'order_number' => 'INV-TEST-EMAIL01',
            'customer_name' => 'Aditya Pratama',
            'customer_email' => 'aditya@example.com',
            'customer_phone' => '081234567890',
            'product_type' => ProductType::Game,
            'target_user_id' => '12345678',
            'target_zone_id' => '2123',
            'target_nickname' => 'AdityaPro',
            'subtotal' => 50000,
            'total_amount' => 50000,
            'status' => OrderStatus::Processing,
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

        Payment::create([
            'order_id' => $order->id,
            'merchant_ref' => $order->order_number,
            'payment_gateway' => 'manual',
            'payment_method' => 'BCA',
            'payment_name' => 'BCA Transfer',
            'amount' => 50000,
            'total_amount' => 50000,
            'status' => PaymentStatus::Pending,
            'payment_proof' => 'payment_proofs/test_proof.jpg',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.orders.confirm', $order));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Assert mail was sent to customer
        Mail::assertSent(PaymentConfirmedMail::class, function ($mail) use ($order) {
            return $mail->hasTo('aditya@example.com')
                && $mail->order->id === $order->id;
        });

        // Test rendered email content
        $mailable = new PaymentConfirmedMail($order->fresh(['items.product', 'items.variant', 'payment']));
        $mailable->assertSeeInHtml('INV-TEST-EMAIL01');
        $mailable->assertSeeInHtml('Aditya Pratama');
        $mailable->assertSeeInHtml('PEMBAYARAN DIKONFIRMASI');
        $mailable->assertSeeInHtml('12345678');
    }
}
