<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories with metrics and management actions.
     */
    public function index(): View
    {
        $categories = Category::withCount('products')
            ->with('parent')
            ->orderBy('sort_order')
            ->latest('id')
            ->get();

        $totalCategories = Category::count();
        $activeCategories = Category::active()->count();
        $totalProducts = Product::count();

        $parentCategories = Category::parentsOnly()->orderBy('name')->get();

        $defaultTypes = [
            ['value' => 'game', 'label' => '🎮 Top-Up Game'],
            ['value' => 'account', 'label' => '🍿 Akun Premium / Streaming'],
            ['value' => 'game_account', 'label' => '👑 Jual Beli Akun Game'],
            ['value' => 'joki', 'label' => '⚔️ Jasa Joki Rank'],
            ['value' => 'preorder', 'label' => '👕 Preorder PDH & Uniform'],
        ];

        return view('admin.categories.index', compact(
            'categories',
            'totalCategories',
            'activeCategories',
            'totalProducts',
            'parentCategories',
            'defaultTypes'
        ));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
            'type' => ['nullable', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:50'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $baseSlug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        if (empty($baseSlug)) {
            $baseSlug = 'kategori-'.time();
        }

        $slug = $baseSlug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        $category = Category::create([
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'] ?? null,
            'icon' => $validated['icon'] ?? '📁',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori baru "'.$category->name.'" berhasil ditambahkan!');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'type' => ['nullable', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:50'],
            'parent_id' => ['nullable', 'exists:categories,id', 'not_in:'.$category->id],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['slug']);
        if (empty($slug)) {
            $slug = Str::slug($validated['name']);
        }

        $category->update([
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'] ?? null,
            'icon' => $validated['icon'] ?? $category->icon,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori "'.$category->name.'" berhasil diperbarui!');
    }

    /**
     * Toggle active status of the category.
     */
    public function toggle(Category $category): RedirectResponse
    {
        $category->update([
            'is_active' => ! $category->is_active,
        ]);

        $statusLabel = $category->is_active ? 'Aktif' : 'Nonaktif';

        return redirect()->route('admin.categories.index')
            ->with('success', 'Status kategori "'.$category->name.'" berhasil diubah menjadi '.$statusLabel.'!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $categoryName = $category->name;
        $productCount = $category->products()->count();

        // Release child sub-categories if any
        $category->children()->update(['parent_id' => null]);

        // Delete associated products
        $category->products()->delete();

        // Delete the category itself
        $category->delete();

        $message = $productCount > 0
            ? "Kategori '{$categoryName}' beserta {$productCount} produk di dalamnya berhasil dihapus!"
            : "Kategori '{$categoryName}' berhasil dihapus!";

        return redirect()->route('admin.categories.index')
            ->with('success', $message);
    }
}
