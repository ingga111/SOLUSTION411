# Design Document: solustion411 Modern Website & Admin Dashboard Update

- **Date:** 2026-10-23
- **Topic:** Clean & Sleek Modern UI/UX Frontend + Admin Dashboard Analytics Enhancement
- **Status:** Approved

---

## 1. Executive Summary

This project enhances **solustion411**, a Laravel 12 e-commerce store specializing in game top-ups (Mobile Legends, Free Fire, Valorant) and digital accounts. 

The update focuses on two primary areas:
1. **Frontend Modernization**: Refactoring the layout, homepage, product catalog, and checkout flow into a **Clean & Sleek Modern** aesthetic, featuring high legibility, sticky glassmorphic navigation, micro-interactions, responsive mobile layout, and dark/light mode balance.
2. **Admin Dashboard & Analytics Enhancement**: Upgrading the administrator dashboard with rich KPI metric cards, a sales revenue trend chart (using lightweight Chart.js), digital account low-stock alert monitoring, and a streamlined quick-action order verification table.

---

## 2. Architecture & Tech Stack Alignment

- **Framework**: Laravel 12 on PHP 8.3.
- **Styling**: Tailwind CSS + Plus Jakarta Sans typography.
- **Frontend Interaction**: Vanilla JavaScript micro-interactions (instant search filter, carousel controls, theme switcher, responsive mobile drawer).
- **Visualization**: Chart.js for revenue trend analytics on Admin Dashboard.
- **Testing**: PHPUnit / Laravel Test Suite (must preserve 100% pass rate on all 61+ tests).

---

## 3. Detailed Component Design

### 3.1 Frontend Redesign (Clean & Sleek Modern)

#### A. Global Layout (`resources/views/layouts/app.blade.php`)
- **Sticky Navbar**: Modern glassmorphic header (`backdrop-blur-md bg-darkcard/85 border-b border-darkborder/60`).
- **Brand Identity**: Sharp `solustion411` logo with cyan glow badge and fast transaction status indicators.
- **Quick Actions**: "Cek Pesanan" (Invoice Status check) direct modal/link, search bar with shortcut hint, dark/light theme toggle.
- **Clean Footer**: Organized sitemap, payment method logos, customer support contacts, and legal notices.

#### B. Homepage & Catalog (`resources/views/welcome.blade.php`)
- **Hero Promo Carousel**: Refined banner slider with auto-play, pause on hover, pagination dots, and responsive aspect ratios.
- **Running Text Announcement**: Sleek horizontal ticker with icon badge.
- **Category Filter Tabs**: Pills with product counts (All, Mobile Game, PC Game, Akun Digital, Voucher).
- **Live Search**: Client-side instant filter across game titles and keywords.
- **Product Cards**: Minimalist cards with subtle border highlight on hover, instant delivery indicator, and starting price tag.

#### C. Checkout Experience (`resources/views/orders/checkout.blade.php`)
- **Step 1**: User ID / Zone ID input with validation and helper tooltips.
- **Step 2**: Variant/Nominal selection cards with badge highlights (e.g. Best Seller, Flash Sale).
- **Step 3**: Payment method selection grouped by channel (QRIS, E-Wallet, Virtual Account/Bank Transfer).
- **Sticky Order Summary**: Real-time calculated subtotal, voucher discount, admin fee, and total amount.

---

### 3.2 Admin Dashboard Enhancement

#### A. Metric & KPI Cards (`resources/views/admin/dashboard.blade.php`)
- **Today's Revenue**: Daily revenue with order count indicator.
- **Monthly Revenue**: Month-to-date sales figures.
- **Pending Verification Orders**: Highlighting orders needing admin review.
- **Processing Orders**: Live count of orders undergoing automatic or manual fulfillment.
- **Digital Account Stock & Low-Stock Alerts**: Warning indicator when limited stock variants fall below 3 units.

#### B. Revenue Trends Chart
- **Chart.js Line/Bar Chart**: Displaying daily revenue trends for the current week / month.
- Dark theme-compatible palette (`#06b6d4` cyan and `#6366f1` indigo).

#### C. Quick Actions & Order Management
- Order list preview with customer WhatsApp link, payment proof preview modal/link, and direct 1-click confirm/reject endpoints.

---

## 4. Testing & Quality Assurance

- All existing test suites (`AdminCategoryManagementTest`, `AdminRunningTextManagementTest`, `PreorderPdhFeatureTest`, `OrderFeatureTest`, etc.) must pass.
- Verify responsiveness across mobile (375px), tablet (768px), and desktop (1280px+).
- Verify dark and light theme consistency.
- Code style verification with Laravel Pint (`vendor/bin/pint`).
