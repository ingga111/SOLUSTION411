<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Order\OrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * Export orders to an Excel-compatible CSV file.
     */
    public function export(Request $request): StreamedResponse
    {
        $status = $request->query('status');

        $query = Order::with(['items.product', 'items.variant', 'payment'])->latest();

        if ($status === 'waiting_verification') {
            $query->where('status', OrderStatus::Processing)
                ->whereHas('payment', fn ($q) => $q->whereNotNull('payment_proof')->where('status', '!=', PaymentStatus::Paid));
        } elseif ($status && in_array($status, ['pending', 'processing', 'completed', 'failed', 'cancelled'], true)) {
            $query->where('status', $status);
        }

        $filename = 'laporan-pesanan-solustion411-'.now()->format('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Column Headers
            fputcsv($handle, [
                'No. Invoice',
                'Tanggal Pesanan',
                'Nama Pelanggan',
                'Email Pelanggan',
                'No. HP / WA',
                'Tipe Produk',
                'Item Produk & Varian',
                'Total Nominal (Rp)',
                'Metode Pembayaran',
                'Nomor Rekening Tujuan',
                'Status Pesanan',
                'Status Pembayaran',
                'Catatan / Notes',
            ]);

            $query->chunk(100, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    $itemDescriptions = $order->items->map(function ($item) {
                        return "{$item->product_name} ({$item->variant_name}) x{$item->quantity}";
                    })->implode('; ');

                    fputcsv($handle, [
                        $order->order_number,
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->customer_name,
                        $order->customer_email,
                        $order->customer_phone ?? '-',
                        $order->product_type?->label() ?? $order->product_type?->value ?? '-',
                        $itemDescriptions ?: '-',
                        $order->total_amount,
                        $order->payment_method,
                        $order->payment?->account_number ?? '-',
                        $order->status?->label() ?? $order->status?->value ?? '-',
                        $order->payment?->status?->label() ?? $order->payment?->status?->value ?? '-',
                        $order->notes ?? '-',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, $headers);
    }

    /**
     * Display listing of orders with filters.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = Order::with(['items.product', 'payment'])->latest();

        if ($status === 'waiting_verification') {
            $query->where('status', OrderStatus::Processing)
                ->whereHas('payment', fn ($q) => $q->whereNotNull('payment_proof')->where('status', '!=', PaymentStatus::Paid));
        } elseif ($status && in_array($status, ['pending', 'processing', 'completed', 'failed', 'cancelled'], true)) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Order::count(),
            'waiting_verification' => Order::where('status', OrderStatus::Processing)
                ->whereHas('payment', fn ($q) => $q->whereNotNull('payment_proof')->where('status', '!=', PaymentStatus::Paid))->count(),
            'pending' => Order::where('status', OrderStatus::Pending)->count(),
            'completed' => Order::where('status', OrderStatus::Completed)->count(),
            'failed' => Order::whereIn('status', [OrderStatus::Failed, OrderStatus::Cancelled])->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts', 'status'));
    }

    /**
     * Display order detail with payment proof verification modal/section.
     */
    public function show(Order $order): View
    {
        $order->load(['items.product', 'items.variant', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Confirm customer's manual payment and fulfill order.
     */
    public function confirmPayment(Order $order): RedirectResponse
    {
        try {
            $this->orderService->processPaymentSuccess($order->order_number, [
                'confirmed_by' => auth()->user()?->name ?? 'Admin',
                'confirmed_at' => now()->toIso8601String(),
            ]);

            $order->refresh();
            if ($order->status !== OrderStatus::Completed && ! ($order->isAccount() || $order->isGameAccount())) {
                $order->update([
                    'status' => OrderStatus::Completed,
                    'notes' => 'Pembayaran dikonfirmasi oleh Admin.',
                ]);
            }

            return back()->with('success', "Pembayaran untuk pesanan {$order->order_number} berhasil dikonfirmasi dan pesanan telah selesai!");
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memproses konfirmasi: '.$e->getMessage());
        }
    }

    /**
     * Reject customer's payment proof.
     */
    public function rejectPayment(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $reason = $request->input('reason', 'Bukti pembayaran tidak valid atau transfer belum diterima.');

        $order->update([
            'status' => OrderStatus::Failed,
            'notes' => 'Pembayaran ditolak: '.$reason,
        ]);

        if ($order->payment) {
            $order->payment->update([
                'status' => PaymentStatus::Failed,
                'admin_note' => $reason,
            ]);
        }

        return back()->with('success', "Pesanan {$order->order_number} telah ditandai ditolak.");
    }
}
