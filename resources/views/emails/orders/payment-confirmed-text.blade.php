HALO {{ strtoupper($order->customer_name) }},

Pembayaran Anda untuk pesanan #{{ $order->order_number }} telah berhasil diverifikasi oleh tim solustion411.

RINCIAN PESANAN:
- Nomor Invoice: #{{ $order->order_number }}
- Waktu Transaksi: {{ $order->created_at->format('d M Y, H:i') }} WIB
- Metode Bayar: {{ $order->payment_method ?? 'Transfer Manual' }}
- Status: SELESAI / LUNAS

ITEM PRODUK:
@foreach($order->items as $item)
* {{ $item->product_name }} - {{ $item->variant_name }} ({{ $item->quantity }}x) : Rp {{ number_format($item->subtotal, 0, ',', '.') }}
@if($order->isPreorder())
  Ukuran: {{ $order->clothing_size ?: '-' }} | Tipe: {{ $order->sleeve_type ?: '-' }} | Bordir: "{{ $order->embroidery_name ?: '-' }}"
@elseif($order->isGame() || (!empty($order->target_user_id) && !$order->isPreorder()))
  ID Akun/Game: {{ $order->target_user_id }}{{ $order->target_zone_id ? ' ('.$order->target_zone_id.')' : '' }} | Nickname: {{ $order->target_nickname ?: '-' }}
@endif
@endforeach

RINCIAN PEMBAYARAN:
- Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}
@if($order->discount_amount > 0)
- Diskon Voucher ({{ $order->voucher_code }}): - Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
@endif
@if($order->admin_fee > 0)
- Biaya Layanan: Rp {{ number_format($order->admin_fee, 0, ',', '.') }}
@endif
- TOTAL BAYAR (LUNAS): Rp {{ number_format($order->total_amount, 0, ',', '.') }}

Lihat detail pesanan dan invoice Anda di:
{{ route('orders.show', $order->order_number) }}

Butuh bantuan? Hubungi WhatsApp resmi kami:
https://wa.me/6285150718231 (0851-5071-8231)

Terima kasih telah bertransaksi di solustion411!
© {{ date('Y') }} solustion411 - Platform Top Up & Akun Digital Presisi.

