<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RuanganController extends Controller
{
    public function index(): View
    {
        $ruangan = Ruangan::orderBy('nama')->get();

        return view('ruangan.index', compact('ruangan'));
    }

    public function create(): View
    {
        return view('ruangan.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:ruangans,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'gedung' => ['required', 'string', 'max:60'],
            'lantai' => ['nullable', 'string', 'max:20'],
            'fasilitas' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        $data['fasilitas'] = $this->normalisasiFasilitas($data['fasilitas'] ?? null);
        $data['is_active'] = $request->has('is_active');
        Ruangan::create($data);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Ruangan $ruangan): View
    {
        return view('ruangan.form', compact('ruangan'));
    }

    public function update(Request $request, Ruangan $ruangan): RedirectResponse
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:ruangans,kode,'.$ruangan->id],
            'nama' => ['required', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'gedung' => ['required', 'string', 'max:60'],
            'lantai' => ['nullable', 'string', 'max:20'],
            'fasilitas' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['fasilitas'] = $this->normalisasiFasilitas($data['fasilitas'] ?? null);
        $data['is_active'] = $request->has('is_active');
        $ruangan->update($data);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan): RedirectResponse
    {
        if ($ruangan->jadwals()->count() > 0) {
            return back()->withErrors(['Hapus gagal: Ruangan ini masih terikat pada jadwal aktif.']);
        }
        $ruangan->delete();

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus.');
    }

    /**
     * Mengubah teks fasilitas dipisah koma menjadi array bersih.
     *
     * @return array<int, string>|null
     */
    private function normalisasiFasilitas(?string $fasilitas): ?array
    {
        if ($fasilitas === null || trim($fasilitas) === '') {
            return null;
        }

        $items = array_map(trim(...), explode(',', $fasilitas));
        $items = array_values(array_filter($items, fn (string $item): bool => $item !== ''));

        return $items === [] ? null : $items;
    }
}
