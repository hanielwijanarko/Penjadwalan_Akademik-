<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Services\JadwalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JadwalDropdownController extends Controller
{
    public function __construct(private readonly JadwalService $jadwalService) {}

    public function dosen(Request $request): JsonResponse
    {
        $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'hari' => ['required', 'string'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'abaikan_jadwal_id' => ['nullable', 'exists:jadwals,id'],
        ]);

        $mataKuliah = MataKuliah::query()->findOrFail($request->integer('mata_kuliah_id'));
        $semester = Semester::query()->aktif()->firstOrFail();

        $dosen = $this->jadwalService->dosenPengampu(
            $mataKuliah,
            $request->string('hari')->toString(),
            $request->string('jam_mulai')->toString(),
            $mataKuliah->sks,
            $semester->id,
            $request->integer('abaikan_jadwal_id', 0) ?: null,
        );

        return response()->json($dosen);
    }

    public function ruangan(Request $request): JsonResponse
    {
        $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'hari' => ['required', 'string'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'abaikan_jadwal_id' => ['nullable', 'exists:jadwals,id'],
        ]);

        $mataKuliah = MataKuliah::query()->findOrFail($request->integer('mata_kuliah_id'));
        $semester = Semester::query()->aktif()->firstOrFail();

        $ruangan = $this->jadwalService->ruanganTersedia(
            $request->string('hari')->toString(),
            $request->string('jam_mulai')->toString(),
            $mataKuliah->sks,
            $semester->id,
            $request->integer('abaikan_jadwal_id', 0) ?: null,
        );

        return response()->json($ruangan);
    }
}
