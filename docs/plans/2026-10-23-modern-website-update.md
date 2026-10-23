# Modern Website & Admin Dashboard Implementation Plan

> **For Antigravity:** REQUIRED WORKFLOW: Use `.agent/workflows/execute-plan.md` to execute this plan in single-flow mode.

**Goal:** Transform the website frontend into a clean, sleek modern aesthetic and upgrade the admin dashboard with rich KPI metrics, visual sales trend chart, and low-stock alerts while maintaining 100% test pass rate.

**Architecture:** Utilize Laravel Blade with responsive Tailwind CSS utilities and vanilla JS for frontend interactions. Enhance `DashboardController` to aggregate 7-day revenue trends and low-stock alerts, visualizing analytics using lightweight Chart.js.

**Tech Stack:** Laravel 12 (PHP 8.3), Blade, Tailwind CSS, Plus Jakarta Sans, Chart.js, PHPUnit.

---

### Task 1: Admin Dashboard Analytics & Metrics Backend

**Files:**
- Modify: `app/Http/Controllers/Admin/DashboardController.php`
- Create: `tests/Feature/AdminDashboardMetricsTest.php`

**Step 1: Write the failing test**
Create `tests/Feature/AdminDashboardMetricsTest.php` to verify that `DashboardController@index` calculates and passes:
- `lowStockVariants` (count and collection of accounts with stock <= 3)
- `salesChartData` (labels and revenue amounts for the last 7 days)
- Returns HTTP 200 for authenticated admin and redirects for non-admin.

**Step 2: Run test to verify it fails**
Run: `php artisan test --filter=AdminDashboardMetricsTest`
Expected: FAIL (assertion failed or missing view variables)

**Step 3: Implement DashboardController updates**
In `app/Http/Controllers/Admin/DashboardController.php`:
- Query `lowStockVariants = ProductVariant::where('stock_type', '!=', 'unlimited')->where('stock', '<=', 3)->get()`
- Build `salesChartData` for the past 7 days (date labels and daily total amount)
- Pass variables to `admin.dashboard` view.

**Step 4: Run test to verify it passes**
Run: `php artisan test --filter=AdminDashboardMetricsTest`
Expected: PASS

**Step 5: Commit changes**
Run:
```bash
git add app/Http/Controllers/Admin/DashboardController.php tests/Feature/AdminDashboardMetricsTest.php
git commit -m "feat(admin): add low stock alerts and 7-day sales trend metrics to dashboard"
```

---

### Task 2: Modern Admin Dashboard UI & Chart Visualization

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php`

**Step 1: Update view with modern KPI cards, Low-Stock Alert banner, Chart.js, and Quick Actions**
In `resources/views/admin/dashboard.blade.php`:
- Include Chart.js via CDN (`https://cdn.jsdelivr.net/npm/chart.js`).
- Render KPI metric cards with sleek micro-borders and subtle contrast.
- Render low-stock alert banner if `$lowStockVariants->isNotEmpty()`.
- Render canvas chart `#salesTrendChart` configured with dark/light mode harmonious colors.
- Polish recent orders table with status badges and quick action buttons.

**Step 2: Run tests to ensure no regressions**
Run: `php artisan test --compact`
Expected: All tests pass.

**Step 3: Commit changes**
Run:
```bash
git add resources/views/admin/dashboard.blade.php
git commit -m "feat(admin): modernize admin dashboard UI with sales chart and stock alerts"
```

---

### Task 3: Global Layout & Navbar Modernization

**Files:**
- Modify: `resources/views/layouts/app.blade.php`

**Step 1: Redesign header, navigation bar, and footer**
In `resources/views/layouts/app.blade.php`:
- Sticky glassmorphic navbar with refined blur (`backdrop-blur-md bg-darkcard/85`).
- Clean brand typography and logo lockup.
- Quick action "Cek Pesanan" button linking directly to invoice tracker.
- Polished theme toggle (Dark / Light) with smooth transition.
- Clean, structured footer with payment badges and customer support links.

**Step 2: Run test suite**
Run: `php artisan test --compact`
Expected: All tests pass.

**Step 3: Commit changes**
Run:
```bash
git add resources/views/layouts/app.blade.php
git commit -m "feat(frontend): modernize global navbar and layout with clean sleek styling"
```

---

### Task 4: Homepage & Catalog Modernization

**Files:**
- Modify: `resources/views/welcome.blade.php`

**Step 1: Refactor hero carousel, announcement ticker, category pills, and product grid**
In `resources/views/welcome.blade.php`:
- Clean hero carousel banner with smooth auto-play and dot controls.
- Announcement ticker with modern typography.
- Category pills with count badges and active states.
- Instant client-side search filtering game titles in real-time.
- Minimalist product cards with crisp hover effect and starting price tags.

**Step 2: Run test suite**
Run: `php artisan test --compact`
Expected: All tests pass.

**Step 3: Commit changes**
Run:
```bash
git add resources/views/welcome.blade.php
git commit -m "feat(frontend): modernize homepage catalog, search filter, and hero banner"
```

---

### Task 5: Code Quality & Pint Formatting Verification

**Files:**
- All touched PHP files

**Step 1: Run Pint Formatter**
Run: `vendor/bin/pint --format agent`

**Step 2: Run Full Test Suite**
Run: `php artisan test --compact`
Expected: 100% tests pass.

**Step 3: Commit formatting & finalize**
Run:
```bash
git add .
git commit -m "chore: format code with Laravel Pint and verify test suite"
```
