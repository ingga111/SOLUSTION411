# Customer-Side Linear / Raycast Modern Precision Design

**Date:** 2026-11-01  
**Status:** Approved  
**Aesthetic Direction:** Linear / Raycast Modern Precision (Zinc Monochrome, Sharp Typography, Command Bar, Tabular Numbers, Low-Visual Clutter)

---

## 1. Executive Summary & Goals

The user requested a full UI/UX overhaul of the customer/user-facing store to match the sleek, ultra-clean aesthetic of modern developer tools and AI products like **Linear** and **Raycast**:
- **Monochrome Zinc Hierarchy:** Replace generic bright e-commerce styling with deep zinc surfaces (`#09090b` / `bg-zinc-950`), subtle borders (`border-zinc-800`), crisp typography (*Plus Jakarta Sans* + *JetBrains Mono / monospace*), and high-contrast buttons (`bg-zinc-100 text-zinc-950`).
- **Command-Driven Navigation:** Slim sticky header with a Raycast-style prompt search bar (`Ctrl+K`), an ultra-slim news ticker, and clean modal dialogs.
- **Precision Catalog:** Structured hero banners with thin borders, Linear-style segmented category filter pills, and tool-like product cards with tabular price formatting.
- **5-Step Linear Checkout:** Numeric step-by-step layout (`01`, `02`, `03`...), radio tiles for variants and payment methods, instant voucher deductions, and a sticky Stripe/Linear receipt summary.
- **Invoice & Customer Area:** Professional receipts with instant 1-click copy buttons, QRIS barcode showcase, and structured order history.

---

## 2. Design System Tokens & Foundations

### Color Tokens
| Role | Dark Mode (Default) | Light Mode |
|---|---|---|
| Background Canvas | `bg-zinc-950` (`#09090b`) | `bg-zinc-50` (`#fafafa`) |
| Surface / Cards | `bg-zinc-900/80` or `bg-zinc-900` | `bg-white` |
| Secondary Surface | `bg-zinc-950/60` | `bg-zinc-100/70` |
| Borders | `border-zinc-800/80` or `border-zinc-800` | `border-zinc-200/80` |
| High-Contrast Primary Button | `bg-zinc-100 hover:bg-white text-zinc-950` | `bg-zinc-900 hover:bg-zinc-800 text-white` |
| Secondary Button | `bg-zinc-800 hover:bg-zinc-700 text-zinc-200` | `bg-zinc-100 hover:bg-zinc-200 text-zinc-800` |
| Typography (Headings) | `text-zinc-100 font-extrabold tracking-tight` | `text-zinc-900 font-extrabold tracking-tight` |
| Typography (Muted/Sub) | `text-zinc-400 font-normal` | `text-zinc-500 font-normal` |
| Monospace Accents | `font-mono text-xs tabular-nums` | `font-mono text-xs tabular-nums` |
| Status Indicators | Dot pills: emerald for active, amber for pending, rose for failed |

---

## 3. Component Architecture & Detailed Layouts

### 3.1 Global Layout, Header & Footer (`resources/views/layouts/app.blade.php`)
- **Ultra-Slim Running Ticker:** Height ~32px, `bg-zinc-900/90 border-b border-zinc-800/80`, discrete `INFO` badge, monospace broadcast text with smooth animation loop.
- **Slim Sticky Header:** 56px height, `backdrop-blur-md bg-zinc-950/80 border-b border-zinc-800/80`.
- **Command Search Bar:** Raycast-style search bar with subtle magnifying glass icon, `<kbd class="font-mono text-[10px] bg-zinc-800 px-1.5 py-0.5 rounded border border-zinc-700 text-zinc-400">Ctrl K</kbd>`, opening a focused search dropdown/palette.
- **Modals:** Cek Pesanan Cepat & Hitung WR MLBB refactored with clean zinc modal cards, crisp inputs, and monospace calculators.
- **Mobile Dock:** Bottom dock `bg-zinc-950/90 backdrop-blur-md border-t border-zinc-800` with Beranda, Cari, Pesanan, Profil.

### 3.2 Homepage & Catalog Grid (`resources/views/welcome.blade.php`)
- **Hero Banner:** 16:9 / 21:9 container with `rounded-2xl border border-zinc-800/80 overflow-hidden`, dot indicators, and subtle chevron arrows.
- **Segmented Category Control:** Linear-style segmented control pills (`bg-zinc-900/80 border border-zinc-800 p-1 rounded-xl`) with active tab highlight and item counters.
- **Product Catalog Cards:**
  - Standardized aspect ratio thumbnails with rounded corners.
  - Type badges (`font-mono text-[10px] uppercase tracking-wider text-zinc-400 bg-zinc-800/60 px-2 py-0.5 rounded`).
  - Product titles with tight letter-spacing.
  - Price tags with `tabular-nums font-semibold` and discount badge in subtle emerald/zinc.

### 3.3 Detail Produk & 5-Step Checkout (`resources/views/orders/checkout.blade.php`)
- **Structured 2-Column Split:** Left 7-column step-by-step form; Right 5-column sticky invoice card.
- **Numbered Steps:** Minimalist numeric labels (`01 IDENTITAS AKUN`, `02 PILIH NOMINAL`, `03 METODE PEMBAYARAN`, `04 INFORMASI KONTAK`, `05 VOUCHER & RINGKASAN`).
- **Interactive Tiles:**
  - Variants: Grid of radio tiles with clean borders, active state indicator dot, variant name, and formatted price.
  - Payment Methods: Grouped into Bank Transfer and Instant QRIS with clean bank badges and transparent fee calculation.
- **Voucher Box:** Minimalist command-style input with "Gunakan" button and instant AJAX feedback.
- **Mobile Sticky Bar:** Bottom summary bar with current variant, total price, and "Bayar Sekarang" action button.

### 3.4 Invoice & Payment View (`resources/views/orders/show.blade.php`)
- **Receipt Header:** Order number `#TRX-...`, created timestamp, and payment status dot.
- **4-Step Status Tracker:** Sleek horizontal line stepper with status pills.
- **Transfer Instructions:** Large tabular amount with a 1-click copy button (with checkmark animation), bank account card, and QRIS barcode.
- **Payment Proof Form:** Clean drag-and-drop or file upload tile with instant preview and progress state.

### 3.5 Customer Orders, Profile & Auth
- **Customer Orders (`customer/orders/index.blade.php` & `show.blade.php`):** Clean table/list view with status filters, monospace order numbers, and invoice links.
- **Profile (`profile/edit.blade.php`):** Zinc surface card, clean avatar, user statistics, and password change fields.
- **Login & Register (`auth/login.blade.php` & `auth/register.blade.php`):** Centralized clean cards, zinc inputs, Turnstile captcha container, and clean typography.

---

## 4. Verification & Testing Strategy
- Feature Tests: All existing tests in `DigitalOrderAndPaymentTest`, `PreorderPdhFeatureTest`, and `FlashSaleAndVoucherTest` must pass 100%.
- PHPUnit Suite: Total 66 tests passing with 0 errors.
- Code Standards: Run Laravel Pint code formatter (`php vendor/bin/pint --dirty --format agent`).

