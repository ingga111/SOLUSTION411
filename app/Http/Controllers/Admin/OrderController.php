<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\Order\OrderService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
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
     * Apply all search and criteria filters to the order query.
     */
    protected function applyOrderFilters(Builder $query, Request $request): Builder
    {
        // 1. Keyword search (Invoice, Customer Name, Email, Phone, Target ID, Nickname)
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('target_user_id', 'like', "%{$search}%")
                    ->orWhere('target_nickname', 'like', "%{$search}%");
            });
        }

        // 2. Order Status
        $status = $request->query('status');
        if ($status === 'waiting_verification') {
            $query->where('status', OrderStatus::Processing)
                ->whereHas('payment', fn ($q) => $q->whereNotNull('payment_proof')->where('status', '!=', PaymentStatus::Paid));
        } elseif ($status === 'failed') {
            $query->whereIn('status', [OrderStatus::Failed, OrderStatus::Cancelled]);
        } elseif ($status && in_array($status, ['pending', 'processing', 'completed', 'cancelled'], true)) {
            $query->where('status', $status);
        }

        // 3. Product Type (game, preorder, account, game_account, joki)
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->input('product_type'));
        }

        // 4. Specific Product
        if ($request->filled('product_id')) {
            $productId = $request->input('product_id');
            $query->whereHas('items', fn ($q) => $q->where('product_id', $productId));
        }

        // 5. Preorder Clothing Size (stored in target_user_id for preorder)
        if ($request->filled('clothing_size')) {
            $size = trim((string) $request->input('clothing_size'));
            $query->where('target_user_id', $size)
                ->where('product_type', ProductType::Preorder);
        }

        // 6. Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // 7. Payment Method
        if ($request->filled('payment_method')) {
            $method = $request->input('payment_method');
            $query->whereHas('payment', fn ($q) => $q->where('payment_method', $method));
        }

        return $query;
    }

    /**
     * Export orders to an Excel (.xls) or CSV (.csv) file.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = Order::with(['items.product', 'items.variant', 'payment'])->latest();
        $this->applyOrderFilters($query, $request);

        $format = strtolower((string) $request->query('format', 'excel'));

        if ($format === 'csv') {
            return $this->exportCsv($query);
        }

        return $this->exportExcel($query, $request);
    }

    /**
     * Export structured, formatted Microsoft Excel (.xls) spreadsheet.
     */
    protected function exportExcel(Builder $query, Request $request): StreamedResponse
    {
        $filename = 'laporan-pesanan-solustion411-'.now()->format('Y-m-d_His').'.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->streamDownload(function () use ($query, $request) {
            $nowStr = now()->translatedFormat('d F Y, H:i:s');
            $activeFilterDesc = $this->buildFilterSummary($request);

            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">'."\n";
            echo '<head>'."\n";
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'."\n";
            echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Laporan Pesanan</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->'."\n";
            echo '<style>
                body { font-family: Calibri, Arial, sans-serif; font-size: 10pt; }
                table { border-collapse: collapse; }
                .title-main { font-size: 15pt; font-weight: bold; color: #09090b; }
                .meta-label { font-size: 9pt; color: #71717a; }
                .meta-val { font-size: 9pt; font-weight: bold; color: #18181b; }
                th { background-color: #18181b; color: #ffffff; font-weight: bold; border: 1px solid #27272a; padding: 8px 6px; text-align: center; font-size: 9pt; }
                td { border: 1px solid #e4e4e7; padding: 6px; font-size: 9pt; vertical-align: middle; }
                .text { mso-number-format:"\@"; }
                .num { mso-number-format:"\#\,\#\#0"; text-align: right; }
                .center { text-align: center; }
                .badge-completed { background-color: #dcfce7; color: #15803d; font-weight: bold; text-align: center; }
                .badge-pending { background-color: #fef9c3; color: #854d0e; font-weight: bold; text-align: center; }
                .badge-processing { background-color: #fef3c7; color: #92400e; font-weight: bold; text-align: center; }
                .badge-failed { background-color: #fee2e2; color: #991b1b; font-weight: bold; text-align: center; }
                .total-row td { background-color: #f4f4f5; font-weight: bold; border-top: 2px solid #18181b; }
            </style>'."\n";
            echo '</head>'."\n";
            echo '<body>'."\n";

            // Title & Metadata Section
            echo '<table>'."\n";
            echo '<tr><td colspan="26" class="title-main">LAPORAN REKAP TRANSAKSI &amp; PESANAN - SOLUSTION411</td></tr>'."\n";
            echo '<tr><td colspan="26" class="meta-label">Waktu Unduh: <span class="meta-val">'.$nowStr.' WIB</span> &nbsp;|&nbsp; Filter Aktif: <span class="meta-val">'.$activeFilterDesc.'</span></td></tr>'."\n";
            echo '<tr><td colspan="26"></td></tr>'."\n";

            // Header Table
            echo '<tr>'."\n";
            echo '<th>No.</th>'."\n";
            echo '<th>No. Invoice</th>'."\n";
            echo '<th>Tanggal Pesanan</th>'."\n";
            echo '<th>Status Pesanan</th>'."\n";
            echo '<th>Status Pembayaran</th>'."\n";
            echo '<th>Metode Pembayaran</th>'."\n";
            echo '<th>Rekening Tujuan</th>'."\n";
            echo '<th>Nama Pelanggan</th>'."\n";
            echo '<th>No. WhatsApp / HP</th>'."\n";
            echo '<th>Email Pelanggan</th>'."\n";
            echo '<th>Tipe Transaksi</th>'."\n";
            echo '<th>Nama Produk</th>'."\n";
            echo '<th>Variasi / Pilihan Item</th>'."\n";
            echo '<th>Ukuran (Khusus Preorder)</th>'."\n";
            echo '<th>Tipe Lengan (Preorder)</th>'."\n";
            echo '<th>Bordir Nama (Preorder)</th>'."\n";
            echo '<th>User ID Game (Top Up)</th>'."\n";
            echo '<th>Server / Zone ID Game</th>'."\n";
            echo '<th>Nickname Game</th>'."\n";
            echo '<th>Qty</th>'."\n";
            echo '<th>Harga Satuan (Rp)</th>'."\n";
            echo '<th>Subtotal Item (Rp)</th>'."\n";
            echo '<th>Potongan Voucher (Rp)</th>'."\n";
            echo '<th>Biaya Admin (Rp)</th>'."\n";
            echo '<th>Total Bayar (Rp)</th>'."\n";
            echo '<th>Catatan / Notes</th>'."\n";
            echo '</tr>'."\n";

            $rowNum = 1;
            $totalQty = 0;
            $grandTotal = 0;

            $query->chunk(100, function ($orders) use (&$rowNum, &$totalQty, &$grandTotal) {
                foreach ($orders as $order) {
                    $statusClass = match ($order->status) {
                        OrderStatus::Completed => 'badge-completed',
                        OrderStatus::Pending => 'badge-pending',
                        OrderStatus::Processing => 'badge-processing',
                        OrderStatus::Failed, OrderStatus::Cancelled => 'badge-failed',
                        default => 'center',
                    };

                    $items = $order->items;
                    if ($items->isEmpty()) {
                        $grandTotal += (float) $order->total_amount;
                        echo '<tr>';
                        echo '<td class="center">'.$rowNum++.'</td>';
                        echo '<td class="text center">#'.$order->order_number.'</td>';
                        echo '<td class="center">'.$order->created_at->format('Y-m-d H:i').'</td>';
                        echo '<td class="'.$statusClass.'">'.($order->status?->label() ?? $order->status?->value ?? '-').'</td>';
                        echo '<td class="center">'.($order->payment?->status?->label() ?? $order->payment?->status?->value ?? 'Belum Bayar').'</td>';
                        echo '<td class="center">'.e($order->payment_method ?? '-').'</td>';
                        echo '<td class="text center">'.e($order->payment?->account_number ?? '-').'</td>';
                        echo '<td>'.e($order->customer_name).'</td>';
                        echo '<td class="text">'.e($order->customer_phone ?? '-').'</td>';
                        echo '<td>'.e($order->customer_email).'</td>';
                        echo '<td class="center">'.($order->product_type?->label() ?? $order->product_type?->value ?? '-').'</td>';
                        echo '<td>-</td>';
                        echo '<td>-</td>';
                        echo '<td class="center text">'.e($order->isPreorder() ? ($order->clothing_size ?: '-') : '-').'</td>';
                        echo '<td class="center text">'.e($order->isPreorder() ? ($order->sleeve_type ?: '-') : '-').'</td>';
                        echo '<td>'.e($order->isPreorder() ? ($order->embroidery_name ?: '-') : '-').'</td>';
                        echo '<td class="text center">'.e(! $order->isPreorder() ? ($order->target_user_id ?: '-') : '-').'</td>';
                        echo '<td class="text center">'.e(! $order->isPreorder() && $order->isGame() ? ($order->target_zone_id ?: '-') : '-').'</td>';
                        echo '<td>'.e(! $order->isPreorder() ? ($order->target_nickname ?: '-') : '-').'</td>';
                        echo '<td class="num">0</td>';
                        echo '<td class="num">0</td>';
                        echo '<td class="num">0</td>';
                        echo '<td class="num">'.(float) ($order->discount_amount ?: 0).'</td>';
                        echo '<td class="num">'.(float) ($order->admin_fee ?: 0).'</td>';
                        echo '<td class="num">'.(float) $order->total_amount.'</td>';
                        echo '<td>'.e($order->notes ?? '-').'</td>';
                        echo '</tr>'."\n";
                    } else {
                        foreach ($items as $item) {
                            $totalQty += (int) $item->quantity;
                            $grandTotal += (float) $order->total_amount;
                            echo '<tr>';
                            echo '<td class="center">'.$rowNum++.'</td>';
                            echo '<td class="text center">#'.$order->order_number.'</td>';
                            echo '<td class="center">'.$order->created_at->format('Y-m-d H:i').'</td>';
                            echo '<td class="'.$statusClass.'">'.($order->status?->label() ?? $order->status?->value ?? '-').'</td>';
                            echo '<td class="center">'.($order->payment?->status?->label() ?? $order->payment?->status?->value ?? 'Belum Bayar').'</td>';
                            echo '<td class="center">'.e($order->payment_method ?? '-').'</td>';
                            echo '<td class="text center">'.e($order->payment?->account_number ?? '-').'</td>';
                            echo '<td>'.e($order->customer_name).'</td>';
                            echo '<td class="text">'.e($order->customer_phone ?? '-').'</td>';
                            echo '<td>'.e($order->customer_email).'</td>';
                            echo '<td class="center">'.($order->product_type?->label() ?? $order->product_type?->value ?? '-').'</td>';
                            echo '<td>'.e($item->product_name ?? '-').'</td>';
                            echo '<td>'.e($item->variant_name ?? '-').'</td>';
                            echo '<td class="center text font-bold">'.e($order->isPreorder() ? ($order->clothing_size ?: '-') : '-').'</td>';
                            echo '<td class="center text">'.e($order->isPreorder() ? ($order->sleeve_type ?: '-') : '-').'</td>';
                            echo '<td>'.e($order->isPreorder() ? ($order->embroidery_name ?: '-') : '-').'</td>';
                            echo '<td class="text center">'.e(! $order->isPreorder() ? ($order->target_user_id ?: '-') : '-').'</td>';
                            echo '<td class="text center">'.e(! $order->isPreorder() && $order->isGame() ? ($order->target_zone_id ?: '-') : '-').'</td>';
                            echo '<td>'.e(! $order->isPreorder() ? ($order->target_nickname ?: '-') : '-').'</td>';
                            echo '<td class="num">'.(int) $item->quantity.'</td>';
                            echo '<td class="num">'.(float) $item->price.'</td>';
                            echo '<td class="num">'.(float) $item->subtotal.'</td>';
                            echo '<td class="num">'.(float) ($order->discount_amount ?: 0).'</td>';
                            echo '<td class="num">'.(float) ($order->admin_fee ?: 0).'</td>';
                            echo '<td class="num">'.(float) $order->total_amount.'</td>';
                            echo '<td>'.e($order->notes ?? '-').'</td>';
                            echo '</tr>'."\n";
                        }
                    }
                }
            });

            // Summary Total Row
            echo '<tr class="total-row">';
            echo '<td colspan="19" class="center">TOTAL RINGKASAN</td>';
            echo '<td class="num">'.$totalQty.'</td>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td class="num">'.$grandTotal.'</td>';
            echo '<td></td>';
            echo '</tr>'."\n";

            echo '</table>'."\n";
            echo '</body>'."\n";
            echo '</html>'."\n";
        }, $filename, $headers);
    }

    /**
     * Export clean, standardized CSV file with UTF-8 BOM.
     */
    protected function exportCsv(Builder $query): StreamedResponse
    {
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
                'No.',
                'No. Invoice',
                'Tanggal Pesanan',
                'Status Pesanan',
                'Status Pembayaran',
                'Metode Pembayaran',
                'Rekening Tujuan',
                'Nama Pelanggan',
                'No. WhatsApp / HP',
                'Email Pelanggan',
                'Tipe Transaksi',
                'Nama Produk',
                'Variasi / Pilihan Item',
                'Ukuran (Khusus Preorder)',
                'Tipe Lengan (Preorder)',
                'Bordir Nama (Preorder)',
                'User ID Game (Top Up)',
                'Server / Zone ID Game',
                'Nickname Game',
                'Qty',
                'Harga Satuan (Rp)',
                'Subtotal Item (Rp)',
                'Potongan Voucher (Rp)',
                'Biaya Admin (Rp)',
                'Total Bayar Pesanan (Rp)',
                'Catatan / Notes',
            ]);

            $rowNum = 1;
            $query->chunk(100, function ($orders) use ($handle, &$rowNum) {
                foreach ($orders as $order) {
                    $items = $order->items;
                    if ($items->isEmpty()) {
                        fputcsv($handle, [
                            $rowNum++,
                            $order->order_number,
                            $order->created_at->format('Y-m-d H:i:s'),
                            $order->status?->label() ?? $order->status?->value ?? '-',
                            $order->payment?->status?->label() ?? $order->payment?->status?->value ?? 'Belum Bayar',
                            $order->payment_method ?? '-',
                            $order->payment?->account_number ?? '-',
                            $order->customer_name,
                            "'".($order->customer_phone ?? '-'),
                            $order->customer_email,
                            $order->product_type?->label() ?? $order->product_type?->value ?? '-',
                            '-',
                            '-',
                            $order->isPreorder() ? ($order->clothing_size ?: '-') : '-',
                            $order->isPreorder() ? ($order->sleeve_type ?: '-') : '-',
                            $order->isPreorder() ? ($order->embroidery_name ?: '-') : '-',
                            ! $order->isPreorder() ? ($order->target_user_id ?: '-') : '-',
                            ! $order->isPreorder() && $order->isGame() ? ($order->target_zone_id ?: '-') : '-',
                            ! $order->isPreorder() ? ($order->target_nickname ?: '-') : '-',
                            0,
                            0,
                            0,
                            $order->discount_amount ?: 0,
                            $order->admin_fee ?: 0,
                            $order->total_amount,
                            $order->notes ?? '-',
                        ]);
                    } else {
                        foreach ($items as $item) {
                            fputcsv($handle, [
                                $rowNum++,
                                $order->order_number,
                                $order->created_at->format('Y-m-d H:i:s'),
                                $order->status?->label() ?? $order->status?->value ?? '-',
                                $order->payment?->status?->label() ?? $order->payment?->status?->value ?? 'Belum Bayar',
                                $order->payment_method ?? '-',
                                $order->payment?->account_number ?? '-',
                                $order->customer_name,
                                "'".($order->customer_phone ?? '-'),
                                $order->customer_email,
                                $order->product_type?->label() ?? $order->product_type?->value ?? '-',
                                $item->product_name ?? '-',
                                $item->variant_name ?? '-',
                                $order->isPreorder() ? ($order->clothing_size ?: '-') : '-',
                                $order->isPreorder() ? ($order->sleeve_type ?: '-') : '-',
                                $order->isPreorder() ? ($order->embroidery_name ?: '-') : '-',
                                ! $order->isPreorder() ? ($order->target_user_id ?: '-') : '-',
                                ! $order->isPreorder() && $order->isGame() ? ($order->target_zone_id ?: '-') : '-',
                                ! $order->isPreorder() ? ($order->target_nickname ?: '-') : '-',
                                $item->quantity,
                                $item->price,
                                $item->subtotal,
                                $order->discount_amount ?: 0,
                                $order->admin_fee ?: 0,
                                $order->total_amount,
                                $order->notes ?? '-',
                            ]);
                        }
                    }
                }
            });

            fclose($handle);
        }, $filename, $headers);
    }

    /**
     * Build text summary of active filters for report header.
     */
    protected function buildFilterSummary(Request $request): string
    {
        $parts = [];
        if ($request->filled('status')) {
            $parts[] = 'Status: '.ucfirst($request->input('status'));
        }
        if ($request->filled('product_type')) {
            $parts[] = 'Tipe: '.ucfirst($request->input('product_type'));
        }
        if ($request->filled('product_id')) {
            $product = Product::find($request->input('product_id'));
            if ($product) {
                $parts[] = 'Produk: '.$product->name;
            }
        }
        if ($request->filled('clothing_size')) {
            $parts[] = 'Ukuran: '.$request->input('clothing_size');
        }
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $from = $request->input('date_from', 'Awal');
            $to = $request->input('date_to', 'Sekarang');
            $parts[] = "Periode: {$from} s/d {$to}";
        }
        if ($request->filled('search')) {
            $parts[] = 'Kata Kunci: "'.$request->input('search').'"';
        }

        return ! empty($parts) ? implode(' | ', $parts) : 'Semua Pesanan (Tanpa Filter)';
    }

    /**
     * Display listing of orders with filters.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = Order::with(['items.product', 'items.variant', 'payment'])->latest();
        $this->applyOrderFilters($query, $request);

        $orders = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Order::count(),
            'waiting_verification' => Order::where('status', OrderStatus::Processing)
                ->whereHas('payment', fn ($q) => $q->whereNotNull('payment_proof')->where('status', '!=', PaymentStatus::Paid))->count(),
            'pending' => Order::where('status', OrderStatus::Pending)->count(),
            'completed' => Order::where('status', OrderStatus::Completed)->count(),
            'failed' => Order::whereIn('status', [OrderStatus::Failed, OrderStatus::Cancelled])->count(),
        ];

        $products = Product::orderBy('name')->get(['id', 'name', 'type']);
        $productTypes = ProductType::cases();
        $clothingSizes = ['S', 'M', 'L', 'XL', 'XXL', '3XL'];

        return view('admin.orders.index', compact(
            'orders',
            'counts',
            'status',
            'products',
            'productTypes',
            'clothingSizes'
        ));
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
