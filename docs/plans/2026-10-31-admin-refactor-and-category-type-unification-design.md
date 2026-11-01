# Admin Suite Full Refactor & Category-Service Type Unification Design

## 1. Overview & Objective
Refactor all administrative views in the application to eliminate "AI-slop" (harsh rainbow gradients, inconsistent emojis, excessive pulse animations, missing tabular numerals) and align with the Linear/Stripe design standards set by `baseline-ui` and `interface-design`. Simultaneously, unify Category and Service Type (`ProductType`) so administrators only select the Category when managing products, and the system automatically determines the associated service workflow and dynamic forms.

## 2. Aesthetics & Design System
- **Surfaces & Cards**: `bg-zinc-900/60 border border-zinc-800 rounded-xl` with quiet hover transitions (`hover:border-zinc-700 hover:bg-zinc-900/80`).
- **Primary Buttons**: `bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs px-3.5 py-1.5 rounded-lg shadow-sm`.
- **Secondary Buttons**: `bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white border border-zinc-700/60 font-medium text-xs px-3 py-1.5 rounded-lg`.
- **Form Controls**: `bg-zinc-900 border border-zinc-800 text-zinc-100 placeholder-zinc-500 rounded-lg px-3.5 py-2 text-xs focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600`.
- **Icons**: Monochrome Lucide-style SVG icons with 1.5 stroke width, replacing all emojis.
- **Numbers & Data**: All prices, timestamps, counts, and invoice numbers use `tabular-nums tracking-tight` and font-mono where appropriate.
- **Badges**: Minimalist pills with dot indicators.

## 3. Category & Service Type Smart Unification
- In `resources/views/admin/products/create.blade.php` and `edit.blade.php`:
  - Categories carry their type metadata: `data-type="{{ $category->type }}"`.
  - When a Category is chosen, the `type` is automatically set, and the form dynamically displays the appropriate fields (User ID / Zone ID for games, credential stock management for accounts, size/bordir for preorders).
  - Redundant secondary "Tipe Produk" selection is eliminated or automated.
  - In `ProductController`, if `type` is omitted or matching the category, the system defaults `type` from the chosen `Category->type`.
- In `resources/views/admin/products/index.blade.php`:
  - Category and Type columns are unified into a single clean column: Category name with a subtle service badge.

## 4. Execution Phases
1. **Phase 1: Products & Categories + Type Unification**
   - `app/Http/Controllers/Admin/ProductController.php`
   - `resources/views/admin/products/index.blade.php`
   - `resources/views/admin/products/create.blade.php`
   - `resources/views/admin/products/edit.blade.php`
   - `resources/views/admin/categories/index.blade.php`
2. **Phase 2: Orders & Payment Verification**
   - `resources/views/admin/orders/index.blade.php`
   - `resources/views/admin/orders/show.blade.php`
3. **Phase 3: Finance & Marketing Modules**
   - `resources/views/admin/payment-methods/index.blade.php`, `create.blade.php`, `edit.blade.php`
   - `resources/views/admin/flash-sales/index.blade.php`
   - `resources/views/admin/vouchers/index.blade.php`
   - `resources/views/admin/banners/index.blade.php`
   - `resources/views/admin/running-texts/index.blade.php`
4. **Phase 4: User Management & Quality Assurance**
   - `resources/views/admin/users/index.blade.php`, `create.blade.php`, `edit.blade.php`
   - Verification via PHPUnit tests and Pint code formatting.
