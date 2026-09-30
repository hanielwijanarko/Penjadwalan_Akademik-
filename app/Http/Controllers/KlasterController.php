<?php

namespace App\Http\Controllers;

use App\Models\Klaster;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KlasterController extends Controller
{
    public function index(): View
    {
        $klasters = Klaster::latest()->paginate(10);

        return view('klaster.index', compact('klasters'));
    }

    public function create(): View
    {
        return view('klaster.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:klasters,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        Klaster::create($valid);

        return redirect()->route('klaster.index')->with('success', 'Klaster Keilmuan berhasil ditambahkan.');
    }

    public function edit(Klaster $klaster): View
    {
        return view('klaster.form', compact('klaster'));
    }

    public function update(Request $request, Klaster $klaster): RedirectResponse
    {
        $valid = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:klasters,kode,'.$klaster->id],
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $klaster->update(array_merge($valid, [
            'is_active' => $request->boolean('is_active'),
        ]));

        return redirect()->route('klaster.index')->with('success', 'Klaster Keilmuan berhasil diperbarui.');
    }

    public function destroy(Klaster $klaster): RedirectResponse
    {
        $klaster->delete();

        return redirect()->route('klaster.index')->with('success', 'Klaster Keilmuan berhasil dihapus.');
    }
}
