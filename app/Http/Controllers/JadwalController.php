<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Services\JadwalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function __construct(private readonly JadwalService $jadwalService) {}

    public function index(): View
    {
        $semester = Semester::query()->aktif()->first();

        // Lazy load filter & relasi untu preview UI
        $jadwal = $semester ? $semester->jadwals()->with(['mataKuliah', 'dosen', 'ruangan'])->orderBy('hari')->orderBy('jam_mulai')->get() : collect();

        return view('jadwal.index', compact('semester', 'jadwal'));
    }

    public function create(): View
    {
        $semester = Semester::query()->aktif()->firstOrFail();
        $mataKuliah = MataKuliah::query()->where('is_active', true)->orderBy('nama')->get();

        return view('jadwal.form', compact('semester', 'mataKuliah'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'hari' => ['required', 'string'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'dosen_id' => ['required', 'exists:dosens,id'],
            'ruangan_id' => ['required', 'exists:ruangans,id'],
            'kelas' => ['required', 'string', 'max:20'],
            'jumlah_mahasiswa' => ['nullable', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $semester = Semester::query()->aktif()->firstOrFail();
        $data['semester_id'] = $semester->id;

        try {
            $this->jadwalService->simpan($data);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal): View
    {
        $jadwal->load(['mataKuliah', 'dosen', 'ruangan']);
        $semester = Semester::query()->aktif()->firstOrFail();
        $mataKuliah = MataKuliah::query()->where('is_active', true)->orderBy('nama')->get();

        return view('jadwal.form', compact('jadwal', 'semester', 'mataKuliah'));
    }

    public function update(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $data = $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'hari' => ['required', 'string'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'dosen_id' => ['required', 'exists:dosens,id'],
            'ruangan_id' => ['required', 'exists:ruangans,id'],
            'kelas' => ['required', 'string', 'max:20'],
            'jumlah_mahasiswa' => ['nullable', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['semester_id'] = $jadwal->semester_id;

        try {
            $this->jadwalService->perbarui($jadwal, $data);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
