<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\Tv;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_shows_current_prices_without_exposing_unit_access_tokens(): void
    {
        $unit = Tv::create(['name' => 'Unit 1', 'type' => 'ps5', 'price_per_hour' => 25000, 'status' => 'available']);
        Tv::create(['name' => 'Unit 2', 'type' => 'ps5', 'price_per_hour' => 30000, 'status' => 'playing']);
        Setting::set('store_name', 'TambahBang Test');
        Setting::set('store_phone', '0812-3456-7890');

        $this->get('/')->assertOk()
            ->assertSee('TambahBang Test')->assertSee('PlayStation 5')
            ->assertSee('Rp25.000')->assertSee('2 unit')
            ->assertSee('https://wa.me/6281234567890', false)
            ->assertDontSee($unit->access_token);
    }

    public function test_empty_catalog_and_missing_contact_have_honest_fallbacks(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('Pilihan konsol sedang disiapkan.')
            ->assertSee('Kontak online belum tersedia.')
            ->assertDontSee('https://wa.me/', false);
    }
}
