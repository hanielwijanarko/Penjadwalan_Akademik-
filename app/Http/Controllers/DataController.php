<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Modul contoh dengan pemisahan hak akses: `index` boleh diakses semua user
 * login (read-only), sedangkan aksi tulis dikunci role admin lewat route
 * `middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], ['role:admin'])`.
 */
class DataController extends Controller
{
    public function index(): View
    {
        $dataItems = Data::with('creator')->latest()->paginate(10);

        return view('data.index', compact('dataItems'));
    }

    public function create(): View
    {
        return view('data.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $request->user()->dataEntries()->create($valid);

        return redirect()->route('data.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Data $datum): View
    {
        return view('data.form', ['dataItem' => $datum]);
    }

    public function update(Request $request, Data $datum): RedirectResponse
    {
        $valid = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $datum->update($valid);

        return redirect()->route('data.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Data $datum): RedirectResponse
    {
        $datum->delete();

        return redirect()->route('data.index')->with('success', 'Data berhasil dihapus.');
    }
}
