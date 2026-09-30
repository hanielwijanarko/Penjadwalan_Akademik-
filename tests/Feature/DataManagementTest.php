<?php

namespace Tests\Feature;

use App\Models\Data;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->user = User::factory()->create(['role' => User::ROLE_USER]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('data.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_view_data_index_but_cannot_perform_actions(): void
    {
        Data::factory()->create(['created_by' => $this->admin->id]);

        $this->actingAs($this->user)
            ->get(route('data.index'))
            ->assertOk()
            ->assertViewIs('data.index')
            ->assertSee('Baca Saja');

        $this->get(route('data.create'))->assertForbidden();

        $this->post(route('data.store'), [
            'title' => 'Coba',
            'description' => 'Test',
        ])->assertForbidden();

        $data = Data::first();
        $this->delete(route('data.destroy', $data))->assertForbidden();
    }

    public function test_admin_can_perform_crud(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('data.index'))
            ->assertOk()
            ->assertDontSee('Mode Baca Saja');

        $this->get(route('data.create'))->assertOk();

        $this->post(route('data.store'), [
            'title' => 'Data Baru Admin',
            'description' => 'Deskripsi',
        ])->assertRedirect(route('data.index'));
        $this->assertDatabaseHas('data', ['title' => 'Data Baru Admin']);

        $data = Data::first();
        $this->put(route('data.update', $data), [
            'title' => 'Judul Berubah',
            'description' => 'Halo',
        ])->assertRedirect(route('data.index'));
        $this->assertDatabaseHas('data', ['title' => 'Judul Berubah']);

        $this->delete(route('data.destroy', $data))->assertRedirect(route('data.index'));
        $this->assertDatabaseMissing('data', ['id' => $data->id]);
    }
}
