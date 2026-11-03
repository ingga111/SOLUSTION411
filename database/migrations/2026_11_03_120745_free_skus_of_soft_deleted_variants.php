<?php

use App\Models\ProductVariant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $trashed = ProductVariant::onlyTrashed()->get();
        foreach ($trashed as $variant) {
            if (! str_contains($variant->sku, '-deleted-')) {
                $variant->sku = $variant->sku.'-deleted-'.time().'-'.Str::random(4);
                $variant->saveQuietly();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed for freed soft-deleted SKUs
    }
};
