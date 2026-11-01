<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\RunningText;
use App\Models\User;
use Database\Seeders\DigitalProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRunningTextManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DigitalProductSeeder::class);

        $this->admin = User::where('role', UserRole::Admin)->first();
        $this->customer = User::where('role', UserRole::Customer)->first();
    }

    public function test_admin_can_view_running_text_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.running-texts.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Running Text (Teks Pengumuman Berjalan)');
        $response->assertSee('Daftar Pesan Running Text');
    }

    public function test_guest_and_customer_cannot_access_running_text_management(): void
    {
        // Guest redirect to login
        $guestResponse = $this->get(route('admin.running-texts.index'));
        $guestResponse->assertRedirect(route('login'));

        // Customer forbidden (403)
        $customerResponse = $this->actingAs($this->customer)->get(route('admin.running-texts.index'));
        $customerResponse->assertStatus(403);
    }

    public function test_admin_can_create_new_running_text(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.running-texts.store'), [
            'content' => 'Diskon Kilat 70% Top Up Game Malam Ini Saja!',
            'icon' => '🔥',
            'url' => '#catalog-section',
            'sort_order' => 1,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.running-texts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('running_texts', [
            'content' => 'Diskon Kilat 70% Top Up Game Malam Ini Saja!',
            'icon' => '🔥',
            'url' => '#catalog-section',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_running_text(): void
    {
        $runningText = RunningText::create([
            'content' => 'Teks Lama Pengumuman',
            'icon' => '⚡',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.running-texts.update', $runningText), [
            'content' => 'Teks Pengumuman Telah Diperbarui!',
            'icon' => '📢',
            'url' => 'https://wa.me/628123456789',
            'sort_order' => 5,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.running-texts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('running_texts', [
            'id' => $runningText->id,
            'content' => 'Teks Pengumuman Telah Diperbarui!',
            'icon' => '📢',
            'url' => 'https://wa.me/628123456789',
            'sort_order' => 5,
        ]);
    }

    public function test_admin_can_toggle_running_text_active_status(): void
    {
        $runningText = RunningText::create([
            'content' => 'Teks Toggle Status Test',
            'icon' => '✨',
            'is_active' => true,
        ]);

        // Toggle to inactive
        $response = $this->actingAs($this->admin)->patch(route('admin.running-texts.toggle', $runningText));
        $response->assertRedirect(route('admin.running-texts.index'));
        $this->assertDatabaseHas('running_texts', [
            'id' => $runningText->id,
            'is_active' => false,
        ]);

        // Toggle back to active
        $response2 = $this->actingAs($this->admin)->patch(route('admin.running-texts.toggle', $runningText));
        $response2->assertRedirect(route('admin.running-texts.index'));
        $this->assertDatabaseHas('running_texts', [
            'id' => $runningText->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_delete_running_text(): void
    {
        $runningText = RunningText::create([
            'content' => 'Teks Yang Akan Dihapus',
            'icon' => '🗑️',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.running-texts.destroy', $runningText));

        $response->assertRedirect(route('admin.running-texts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('running_texts', [
            'id' => $runningText->id,
        ]);
    }

    public function test_active_running_texts_are_displayed_in_public_layout(): void
    {
        RunningText::truncate();

        RunningText::create([
            'content' => 'Promo Spesial Super Unik 2026',
            'icon' => '💎',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('id="ticker-broadcast-bar"', false);
        $response->assertSee('Promo Spesial Super Unik 2026');
        $response->assertSee('💎');
    }

    public function test_inactive_running_texts_are_not_displayed_in_public_layout(): void
    {
        RunningText::truncate();

        RunningText::create([
            'content' => 'Pesan Rahasia Yang Nonaktif',
            'icon' => '🔒',
            'is_active' => false,
            'sort_order' => 1,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertDontSee('Pesan Rahasia Yang Nonaktif');
    }

    public function test_ticker_bar_is_hidden_when_all_running_texts_are_inactive(): void
    {
        RunningText::query()->update(['is_active' => false]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertDontSee('id="ticker-broadcast-bar"', false);
    }
}
