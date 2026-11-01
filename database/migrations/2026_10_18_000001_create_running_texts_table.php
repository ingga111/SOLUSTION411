<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('running_texts', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->string('icon')->nullable()->default('⚡');
            $table->string('url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        $now = now();
        DB::table('running_texts')->insert([
            [
                'icon' => '⚡',
                'content' => 'Proses Otomatis 1-3 Detik 24 Jam Nonstop!',
                'url' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'icon' => '🎮',
                'content' => 'Top Up Diamond Mobile Legends, Free Fire, Valorant, & Genshin Impact Termurah!',
                'url' => '#catalog-section',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'icon' => '👑',
                'content' => 'Jual Beli Akun Sultan & Smurf Bergaransi Anti-Hackback 100%!',
                'url' => '#group-game_account',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'icon' => '🍿',
                'content' => 'Akun Netflix 4K UHD Pilihan 1 Hari, 3 Hari, 7 Hari & 1 Bulan Siap Pakai!',
                'url' => '#group-account',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'icon' => '💬',
                'content' => 'CS Aktif Siap Membantu via WhatsApp Resmi.',
                'url' => null,
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('running_texts');
    }
};
