<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RunningText;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class RunningTextController extends Controller
{
    /**
     * Ensure the running_texts table exists safely without touching any other tables or data.
     */
    protected function ensureTableExists(): void
    {
        if (! Schema::hasTable('running_texts')) {
            try {
                Artisan::call('migrate', [
                    '--path' => 'database/migrations/2026_10_18_000001_create_running_texts_table.php',
                    '--force' => true,
                ]);
            } catch (\Throwable $e) {
                // Handled gracefully
            }
        }
    }

    /**
     * Display a listing of running texts with live preview.
     */
    public function index(): View
    {
        $this->ensureTableExists();

        if (! Schema::hasTable('running_texts')) {
            $runningTexts = collect();
            $totalTexts = 0;
            $activeTexts = 0;

            return view('admin.running-texts.index', compact('runningTexts', 'totalTexts', 'activeTexts'));
        }

        $runningTexts = RunningText::orderBy('sort_order')
            ->latest('id')
            ->get();

        $totalTexts = RunningText::count();
        $activeTexts = RunningText::active()->count();

        return view('admin.running-texts.index', compact(
            'runningTexts',
            'totalTexts',
            'activeTexts'
        ));
    }

    /**
     * Store a newly created running text in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:50'],
            'url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->ensureTableExists();

        RunningText::create([
            'content' => $validated['content'],
            'icon' => $validated['icon'] ?? '⚡',
            'url' => $validated['url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.running-texts.index')
            ->with('success', 'Teks berjalan baru berhasil ditambahkan!');
    }

    /**
     * Update the specified running text in storage.
     */
    public function update(Request $request, RunningText $runningText): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:50'],
            'url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $runningText->update([
            'content' => $validated['content'],
            'icon' => $validated['icon'] ?? $runningText->icon,
            'url' => $validated['url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.running-texts.index')
            ->with('success', 'Teks berjalan berhasil diperbarui!');
    }

    /**
     * Toggle the active status of the running text.
     */
    public function toggle(RunningText $runningText): RedirectResponse
    {
        $runningText->update([
            'is_active' => ! $runningText->is_active,
        ]);

        $statusLabel = $runningText->is_active ? 'Aktif' : 'Nonaktif';

        return redirect()->route('admin.running-texts.index')
            ->with('success', 'Status teks berjalan berhasil diubah menjadi '.$statusLabel.'!');
    }

    /**
     * Remove the specified running text from storage.
     */
    public function destroy(RunningText $runningText): RedirectResponse
    {
        $runningText->delete();

        return redirect()->route('admin.running-texts.index')
            ->with('success', 'Teks berjalan berhasil dihapus!');
    }
}
