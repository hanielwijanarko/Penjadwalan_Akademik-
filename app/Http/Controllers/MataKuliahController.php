<?php

namespace App\Http\Controllers;

use App\Models\Klaster;
use App\Models\MataKuliah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MataKuliahController extends Controller
{
    public function index(): View
    {
        $mataKuliah = MataKuliah::with('klaster')->orderBy('nama')->get();

        return view('matakuliah.index', compact('mataKuliah'));
    }

    public function create(): View
    {
        $klasters = Klaster::orderBy('nama')->get();
        return view('matakuliah.form', compact('klasters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:mata_kuliahs,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1', 'max:6'],
            'klaster_id' => ['required', 'exists:klasters,id'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean']
        ]);

        $data['is_active'] = $request->has('is_active');
        MataKuliah::create($data);

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $mataKuliah): View
    {
        $klasters = Klaster::orderBy('nama')->get();
        return view('matakuliah.form', compact('mataKuliah', 'klasters'));
    }

    public function update(Request $request, MataKuliah $mataKuliah): RedirectResponse
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:mata_kuliahs,kode,'.$mataKuliah->id],
            'nama' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1', 'max:6'],
            'klaster_id' => ['required', 'exists:klasters,id'],
            'deskripsi' => ['nullable', 'string', 'max:1000']
        ]);

        $data['is_active'] = $request->has('is_active');
        $mataKuliah->update($data);

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah): RedirectResponse
    {
        if ($mataKuliah->jadwals()->count() > 0) {
            return back()->withErrors(['Hapus gagal: Mata kuliah ini masih terikat pada jadwal.']);
        }
        $mataKuliah->delete();

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
