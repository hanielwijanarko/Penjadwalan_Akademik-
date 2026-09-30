<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Klaster;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DosenController extends Controller
{
    public function index(): View
    {
        $dosen = Dosen::with('klasters')->orderBy('nama')->get();
        return view('dosen.index', compact('dosen'));
    }

    public function create(): View
    {
        $klasters = Klaster::orderBy('nama')->get();
        return view('dosen.form', compact('klasters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nidn' => ['required', 'string', 'max:50', 'unique:dosens,nidn'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:dosens,email'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'klasters' => ['required', 'array'],
            'klasters.*' => ['exists:klasters,id'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $dosen = Dosen::create($data);
        $dosen->klasters()->sync($request->klasters);

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan beserta afiliasi klasternya.');
    }

    public function edit(Dosen $dosen): View
    {
        $klasters = Klaster::orderBy('nama')->get();
        $dosenSelectedKlasters = $dosen->klasters->pluck('id')->toArray();
        return view('dosen.form', compact('dosen', 'klasters', 'dosenSelectedKlasters'));
    }

    public function update(Request $request, Dosen $dosen): RedirectResponse
    {
        $data = $request->validate([
            'nidn' => ['required', 'string', 'max:50', 'unique:dosens,nidn,'.$dosen->id],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:dosens,email,'.$dosen->id],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'klasters' => ['required', 'array'],
            'klasters.*' => ['exists:klasters,id'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $dosen->update($data);
        $dosen->klasters()->sync($request->klasters);

        return redirect()->route('dosen.index')->with('success', 'Profil dan klaster dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        if ($dosen->jadwals()->count() > 0) {
            return back()->withErrors(['Hapus gagal: Dosen ini masih terikat target penjadwalan. Keluarkan dari jadwal terlebih dahulu.']);
        }
        $dosen->klasters()->detach();
        $dosen->delete();

        return redirect()->route('dosen.index')->with('success', 'Profil dosen berhasil dihapus.');
    }
}
