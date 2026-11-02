<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Dikonfirmasi - solustion411</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #09090b;
            color: #f4f4f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-collapse: collapse;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #121215;
            border: 1px solid #27272a;
            border-radius: 16px;
            overflow: hidden;
        }
        .header {
            padding: 32px 32px 24px 32px;
            border-bottom: 1px solid #27272a;
            background: linear-gradient(180deg, #18181b 0%, #121215 100%);
            text-align: center;
        }
        .brand-logo {
            display: inline-block;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #ffffff;
            text-decoration: none;
        }
        .brand-badge {
            display: inline-block;
            font-size: 10px;
            text-transform: uppercase;
            padding: 2px 6px;
            border-radius: 4px;
            background-color: #27272a;
            color: #a1a1aa;
            margin-left: 4px;
            vertical-align: middle;
        }
        .content {
            padding: 32px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 9999px;
            background-color: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
        }
        .lead-text {
            font-size: 14px;
            line-height: 1.6;
            color: #a1a1aa;
            margin-bottom: 24px;
        }
        .meta-card {
            background-color: #18181b;
            border: 1px solid #27272a;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 6px 0;
            border-bottom: 1px solid #27272a;
        }
        .meta-row:last-child {
            border-bottom: none;
        }
        .meta-label {
            color: #71717a;
        }
        .meta-value {
            font-weight: 600;
            color: #f4f4f5;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
        .items-table {
            width: 100%;
            margin-bottom: 24px;
            border: 1px solid #27272a;
            border-radius: 12px;
            overflow: hidden;
        }
        .items-table th {
            background-color: #18181b;
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #71717a;
            border-bottom: 1px solid #27272a;
        }
        .items-table td {
            padding: 16px;
            font-size: 13px;
            border-bottom: 1px solid #27272a;
            background-color: #121215;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .product-name {
            font-weight: 600;
            color: #ffffff;
            font-size: 14px;
            margin-bottom: 4px;
        }
        .product-variant {
            color: #a1a1aa;
            font-size: 12px;
            margin-bottom: 6px;
        }
        .detail-pill {
            display: inline-block;
            font-size: 11px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            padding: 3px 8px;
            border-radius: 6px;
            margin-right: 4px;
            margin-top: 4px;
        }
        .pill-game {
            background-color: rgba(6, 182, 212, 0.15);
            border: 1px solid rgba(6, 182, 212, 0.3);
            color: #22d3ee;
        }
        .pill-preorder {
            background-color: rgba(217, 70, 239, 0.15);
            border: 1px solid rgba(217, 70, 239, 0.3);
            color: #e879f9;
        }
        .price-summary {
            background-color: #18181b;
            border: 1px solid #27272a;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 28px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 4px 0;
            color: #a1a1aa;
        }
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            padding-top: 12px;
            margin-top: 8px;
            border-top: 1px solid #27272a;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
        .btn-cta {
            display: block;
            text-align: center;
            background-color: #ffffff;
            color: #09090b !important;
            font-weight: 700;
            font-size: 13px;
            padding: 14px 24px;
            border-radius: 10px;
            text-decoration: none;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
        }
        .footer {
            padding: 24px 32px;
            background-color: #0d0d10;
            border-top: 1px solid #27272a;
            text-align: center;
            font-size: 12px;
            color: #71717a;
            line-height: 1.6;
        }
        .footer a {
            color: #a1a1aa;
            text-decoration: underline;
        }
    </style>
</head>
<body style="margin: 0; padding: 24px 0; background-color: #09090b;">
    <div class="container">
        <!-- Header -->
        <div class="header">
            <span class="brand-logo">⚡ solustion<span style="color: #71717a;">411</span></span>
            <span class="brand-badge">Official Store</span>
        </div>

        <!-- Content -->
        <div class="content">
            <div style="text-align: center;">
                <span class="status-badge">✓ PEMBAYARAN DIKONFIRMASI</span>
            </div>

            <h1 class="greeting">Halo, {{ $order->customer_name }}! 👋</h1>
            <p class="lead-text">
                Terima kasih atas pesanan Anda. Pembayaran untuk transaksi ini telah <strong>berhasil diverifikasi</strong> oleh tim admin kami dan pesanan telah diselesaikan.
            </p>

            <!-- Metadata Box -->
            <table width="100%" class="meta-card" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="meta-label" style="padding: 6px 0; font-size: 13px; color: #71717a;">Nomor Invoice</td>
                    <td class="meta-value" style="padding: 6px 0; font-size: 13px; text-align: right; font-weight: 700; color: #ffffff; font-family: monospace;">#{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td class="meta-label" style="padding: 6px 0; font-size: 13px; color: #71717a; border-top: 1px solid #27272a;">Waktu Transaksi</td>
                    <td class="meta-value" style="padding: 6px 0; font-size: 13px; text-align: right; color: #f4f4f5; border-top: 1px solid #27272a;">{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td class="meta-label" style="padding: 6px 0; font-size: 13px; color: #71717a; border-top: 1px solid #27272a;">Metode Pembayaran</td>
                    <td class="meta-value" style="padding: 6px 0; font-size: 13px; text-align: right; color: #f4f4f5; border-top: 1px solid #27272a;">{{ $order->payment_method ?? 'Transfer Manual' }}</td>
                </tr>
                <tr>
                    <td class="meta-label" style="padding: 6px 0; font-size: 13px; color: #71717a; border-top: 1px solid #27272a;">Status Pesanan</td>
                    <td class="meta-value" style="padding: 6px 0; font-size: 13px; text-align: right; color: #34d399; font-weight: 700; border-top: 1px solid #27272a;">SELESAI / LUNAS</td>
                </tr>
            </table>

            <!-- Order Items Table -->
            <table class="items-table" width="100%" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th style="padding: 12px 16px; background-color: #18181b; color: #71717a; font-size: 11px; text-transform: uppercase;">Item Produk</th>
                        <th style="padding: 12px 16px; background-color: #18181b; color: #71717a; font-size: 11px; text-transform: uppercase; text-align: center;">Qty</th>
                        <th style="padding: 12px 16px; background-color: #18181b; color: #71717a; font-size: 11px; text-transform: uppercase; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td style="padding: 16px; border-top: 1px solid #27272a;">
                                <div class="product-name">{{ $item->product_name }}</div>
                                <div class="product-variant">Varian: {{ $item->variant_name }}</div>

                                @if($order->isPreorder())
                                    <div>
                                        <span class="detail-pill pill-preorder">👕 Ukuran: {{ $order->clothing_size ?: '-' }}</span>
                                        @if($order->sleeve_type)
                                            <span class="detail-pill pill-preorder">{{ $order->sleeve_type }}</span>
                                        @endif
                                        @if($order->embroidery_name)
                                            <span class="detail-pill pill-preorder">Bordir: "{{ $order->embroidery_name }}"</span>
                                        @endif
                                    </div>
                                @elseif($order->isGame() || (!empty($order->target_user_id) && !$order->isPreorder()))
                                    <div>
                                        @if($order->target_user_id)
                                            <span class="detail-pill pill-game">ID: {{ $order->target_user_id }}{{ $order->target_zone_id ? ' ('.$order->target_zone_id.')' : '' }}</span>
                                        @endif
                                        @if($order->target_nickname)
                                            <span class="detail-pill pill-game">{{ $order->target_nickname }}</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 16px; text-align: center; color: #a1a1aa; font-family: monospace; border-top: 1px solid #27272a;">
                                {{ $item->quantity }}x
                            </td>
                            <td style="padding: 16px; text-align: right; font-weight: 600; color: #ffffff; font-family: monospace; border-top: 1px solid #27272a;">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Financial Summary -->
            <table width="100%" class="price-summary" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding: 4px 0; font-size: 13px; color: #a1a1aa;">Subtotal Produk</td>
                    <td style="padding: 4px 0; font-size: 13px; text-align: right; color: #f4f4f5; font-family: monospace;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($order->discount_amount > 0)
                    <tr>
                        <td style="padding: 4px 0; font-size: 13px; color: #34d399;">Diskon Voucher ({{ $order->voucher_code }})</td>
                        <td style="padding: 4px 0; font-size: 13px; text-align: right; color: #34d399; font-family: monospace;">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if($order->admin_fee > 0)
                    <tr>
                        <td style="padding: 4px 0; font-size: 13px; color: #a1a1aa;">Biaya Layanan / Admin</td>
                        <td style="padding: 4px 0; font-size: 13px; text-align: right; color: #f4f4f5; font-family: monospace;">Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr>
                    <td style="padding-top: 12px; font-size: 15px; font-weight: 700; color: #ffffff; border-top: 1px solid #27272a;">Total Pembayaran</td>
                    <td style="padding-top: 12px; font-size: 16px; font-weight: 700; text-align: right; color: #34d399; font-family: monospace; border-top: 1px solid #27272a;">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </table>

            <!-- CTA Button -->
            <a href="{{ route('orders.show', $order->order_number) }}" class="btn-cta" target="_blank">
                Lihat Invoice &amp; Detail Transaksi ↗
            </a>

            <!-- Help Notice -->
            <div style="background-color: #18181b; border: 1px solid #27272a; border-radius: 10px; padding: 14px 16px; font-size: 12px; color: #a1a1aa; line-height: 1.5; text-align: center;">
                💬 Butuh bantuan dengan pesanan Anda? Hubungi CS kami di WhatsApp resmi: 
                <a href="https://wa.me/6285150718231?text=Halo%20Admin%20solustion411,%20saya%20butuh%20bantuan%20terkait%20pesanan%20%23{{ $order->order_number }}" style="color: #38bdf8; font-weight: 600; text-decoration: none;">
                    0851-5071-8231
                </a>.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 6px 0;">Email ini dikirim secara otomatis oleh sistem <strong>solustion411</strong>.</p>
            <p style="margin: 0; color: #52525b; font-size: 11px;">
                &copy; {{ date('Y') }} solustion411. Platform Top Up &amp; Akun Digital Presisi Otomatis.
            </p>
        </div>
    </div>
</body>
</html>

