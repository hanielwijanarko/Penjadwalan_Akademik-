<?php

namespace Tests\Feature;

use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RuanganMasterDataTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_index_merender_fasilitas_array_sebagai_teks(): void
    {
        $ruangan = Ruangan::factory()->create([
            'kode' => 'R-901',
            'fasilitas' => ['Proyektor', 'AC', 'Whiteboard'],
        ]);

        $response = $this->actingAs($this->admin())->get(route('ruangan.index'));

        $response->assertOk();
        $response->assertSee('Proyektor, AC, Whiteboard');
        $response->assertSee($ruangan->nama);
    }

    public function test_edit_form_merender_dan_memprefill_fasilitas(): void
    {
        $ruangan = Ruangan::factory()->create([
            'fasilitas' => ['PC', 'Server DB'],
        ]);

        $response = $this->actingAs($this->admin())->get(route('ruangan.edit', $ruangan));

        $response->assertOk();
        $response->assertSee('PC, Server DB');
        $response->assertSee($ruangan->gedung);
    }

    public function test_store_menyimpan_fasilitas_csv_sebagai_array(): void
    {
        $response = $this->actingAs($this->admin())->post(route('ruangan.store'), [
            'kode' => 'R-999',
            'nama' => 'Ruang Uji',
            'kapasitas' => 25,
            'gedung' => 'Gedung Z',
            'lantai' => '3',
            'fasilitas' => 'Proyektor, AC , Whiteboard',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('ruangan.index'));

        $this->assertDatabaseHas('ruangans', ['kode' => 'R-999', 'gedung' => 'Gedung Z']);

        $ruangan = Ruangan::where('kode', 'R-999')->firstOrFail();
        $this->assertSame(['Proyektor', 'AC', 'Whiteboard'], $ruangan->fasilitas);
        $this->assertSame('Proyektor, AC, Whiteboard', $ruangan->fasilitas_teks);
    }

    public function test_store_memvalidasi_gedung_wajib(): void
    {
        $response = $this->actingAs($this->admin())->post(route('ruangan.store'), [
            'kode' => 'R-998',
            'nama' => 'Tanpa Gedung',
            'kapasitas' => 20,
            'fasilitas' => 'AC',
        ]);

        $response->assertSessionHasErrors('gedung');
        $this->assertDatabaseMissing('ruangans', ['kode' => 'R-998']);
    }

    public function test_hanya_admin_yang_dapat_mengakses_ruangan(): void
    {
        $dosen = User::factory()->create();

        $this->actingAs($dosen)->get(route('ruangan.index'))->assertForbidden();
    }
}
