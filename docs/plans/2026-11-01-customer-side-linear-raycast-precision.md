# Customer-Side Linear / Raycast Modern Precision Implementation Plan

> **For Antigravity:** REQUIRED WORKFLOW: Use `.agent/workflows/execute-plan.md` to execute this plan in single-flow mode.

**Goal:** Transform the customer/user-facing frontend of `solustion411` into a modern, high-precision developer-grade aesthetic inspired by Linear and Raycast (Zinc monochrome, command bar, segmented tabs, tabular numbers, clean cards).

**Architecture:** Update Blade view templates with Tailwind CSS zinc tokens (`bg-zinc-950`, `bg-zinc-900`, `border-zinc-800`), sleek command bar search, numbered 5-step checkout flow, Stripe/Linear style invoice receipt, while preserving all existing routes, Alpine.js / JS logic, form parameter contracts, and controller endpoints.

**Tech Stack:** Laravel 11 / PHP 8.3, Blade, Tailwind CSS, Alpine.js, PHPUnit.

---

### Task 20: Phase 1 — Global Layout, Header, Raycast Command Bar, Ticker & Footer

**Files:**
- Modify: `resources/views/layouts/app.blade.php`
- Test: `tests/Feature/DigitalOrderAndPaymentTest.php`

**Step 1: Inspect and Refactor Layout Template**
Update `resources/views/layouts/app.blade.php`:
- Canvas: `dark:bg-[#09090b] bg-zinc-50 text-zinc-900 dark:text-zinc-100`.
- Slim Ticker: Monospace broadcast text, height ~32px, `bg-zinc-900/90 border-b border-zinc-800/80`.
- Sticky Header: 56px height, `backdrop-blur-md bg-zinc-950/80 border-b border-zinc-800/80`.
- Search: Raycast-style command bar with `<kbd>Ctrl K</kbd>` shortcut and focused palette.
- Modals: Cek Pesanan Cepat & Hitung WR MLBB using clean zinc surfaces and monospace calculators.
- Mobile Dock: Bottom dock with slim stroke icons and active indicators.
- Footer: Linear-style minimal footer with service links and payment methods.

**Step 2: Run verification test**
Run: `php artisan test --filter=DigitalOrderAndPaymentTest`
Expected: PASS (17 tests)

**Step 3: Update task tracker**
Update `docs/plans/task.md` marking Task 20 Completed.

---

### Task 21: Phase 2 — Homepage, Hero Slider, Linear Segmented Category Tabs & Catalog Cards

**Files:**
- Modify: `resources/views/welcome.blade.php`
- Test: `tests/Feature/DigitalOrderAndPaymentTest.php`
- Test: `tests/Feature/PreorderPdhFeatureTest.php`

**Step 1: Refactor Homepage Template**
Update `resources/views/welcome.blade.php`:
- Hero banner container with thin border `border-zinc-800/80`, rounded-2xl, and discreet chevron indicators.
- Flash sale countdown timer with monospace numbers and discount badges.
- Category navigation using Linear segmented pills (`bg-zinc-900/80 border border-zinc-800`).
- Product cards with standardized thumbnail aspect ratio, monospace type tags (`game`, `game_account`, `preorder`, `account`, `joki`), `tabular-nums` price range, and high-contrast hover state.
- Real-time client-side live search with friendly empty state illustration.

**Step 2: Run verification tests**
Run: `php artisan test --filter=DigitalOrderAndPaymentTest`
Expected: PASS

**Step 3: Update task tracker**
Update `docs/plans/task.md` marking Task 21 Completed.

---

### Task 22: Phase 3 — Detail Product, Screenshot Lightbox & 5-Step Linear Checkout

**Files:**
- Modify: `resources/views/orders/checkout.blade.php`
- Test: `tests/Feature/DigitalOrderAndPaymentTest.php`
- Test: `tests/Feature/FlashSaleAndVoucherTest.php`
- Test: `tests/Feature/PreorderPdhFeatureTest.php`

**Step 1: Refactor Checkout Template**
Update `resources/views/orders/checkout.blade.php`:
- Product gallery with screenshot thumbnails and lightbox zoom modal.
- Structured 2-column layout: Left = 5 numbered steps (`01`, `02`, `03`, `04`, `05`), Right = Sticky invoice summary card.
- Step 1: Dynamic target account inputs (Game ID & Zone ID, Preorder PDH size & embroidery, or account notes).
- Step 2: Interactive radio tiles for product variants with live prices and checkmark dots.
- Step 3: Payment method tiles (Bank manual & QRIS) with admin fee badges.
- Step 4: Contact inputs (WhatsApp & Email).
- Step 5: Command-style voucher input with instant AJAX deduction.
- Mobile bottom floating summary dock.

**Step 2: Run verification tests**
Run: `php artisan test --filter=FlashSaleAndVoucherTest`
Expected: PASS

**Step 3: Update task tracker**
Update `docs/plans/task.md` marking Task 22 Completed.

---

### Task 23: Phase 4 — Invoice, 4-Step Stepper & Payment Receipt

**Files:**
- Modify: `resources/views/orders/show.blade.php`
- Test: `tests/Feature/DigitalOrderAndPaymentTest.php`

**Step 1: Refactor Invoice Template**
Update `resources/views/orders/show.blade.php`:
- Stripe / Linear receipt style container with `#TRX-...` order header and status pill.
- 4-step progress stepper with status dots.
- Countdown payment timer.
- 1-click copy buttons for bank account number and exact unique transfer amount.
- QRIS barcode container.
- Payment proof file upload tile with instant preview.
- Live background status polling (`/orders/{orderNumber}/status`).

**Step 2: Run verification test**
Run: `php artisan test --filter=DigitalOrderAndPaymentTest`
Expected: PASS

**Step 3: Update task tracker**
Update `docs/plans/task.md` marking Task 23 Completed.

---

### Task 24: Phase 5 — Customer Area & Auth Views

**Files:**
- Modify: `resources/views/customer/orders/index.blade.php`
- Modify: `resources/views/customer/orders/show.blade.php`
- Modify: `resources/views/profile/edit.blade.php`
- Modify: `resources/views/auth/login.blade.php`
- Modify: `resources/views/auth/register.blade.php`
- Test: `tests/Feature/DigitalOrderAndPaymentTest.php`

**Step 1: Refactor Customer & Auth Views**
- `customer/orders/index.blade.php` & `show.blade.php`: Linear issue-list style orders view with status filter tabs, monospace order numbers, and tabular amounts.
- `profile/edit.blade.php`: User header card with avatar initial, statistics, personal data form, and password change with eye toggles.
- `auth/login.blade.php` & `auth/register.blade.php`: Centralized zinc card, clean inputs, eye toggles, Turnstile CAPTCHA, and high-contrast submit button.

**Step 2: Run verification test**
Run: `php artisan test --filter=DigitalOrderAndPaymentTest`
Expected: PASS

**Step 3: Update task tracker**
Update `docs/plans/task.md` marking Task 24 Completed.

---

### Task 25: Phase 6 — Quality Verification, Pint Code Formatting & Full Test Suite

**Files:**
- Format: modified PHP / Blade templates
- Test: Complete test suite

**Step 1: Run Laravel Pint Code Formatter**
Run: `php vendor/bin/pint --dirty --format agent`
Expected: `{"tool":"pint","result":"passed"}`

**Step 2: Run Full PHPUnit Test Suite**
Run: `php artisan test`
Expected: 66 passed (0 failures)

**Step 3: Update task tracker**
Update `docs/plans/task.md` marking all tasks Completed.

