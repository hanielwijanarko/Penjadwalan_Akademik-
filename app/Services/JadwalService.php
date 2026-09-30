<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\SesiWaktu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JadwalService
{
    public const MENIT_PER_SKS = 50;

    private const FORMAT_JAM = 'H:i';

    public function normalisasiJam(string $jam): string
    {
        return Carbon::parse($jam)->format(self::FORMAT_JAM);
    }

    public function hitungJamSelesai(string $jamMulai, int $sks): string
    {
        return Carbon::createFromFormat(self::FORMAT_JAM, $this->normalisasiJam($jamMulai))
            ->addMinutes($sks * self::MENIT_PER_SKS)
            ->format(self::FORMAT_JAM);
    }

    public function sesiWaktuUntukJam(string $jam): ?SesiWaktu
    {
        $jamPenuh = Carbon::createFromFormat(self::FORMAT_JAM, $this->normalisasiJam($jam))->format('H:i:s');

        return SesiWaktu::query()
            ->where('is_active', true)
            ->where('jam_mulai', '<=', $jamPenuh)
            ->where('jam_selesai', '>=', $jamPenuh)
            ->orderBy('urutan')
            ->first();
    }

    public function dosenPengampu(MataKuliah $mataKuliah, string $hari, string $jamMulai, int $sks, int $semesterId, ?int $abaikanJadwalId = null): Collection
    {
        $jamMulai = $this->normalisasiJam($jamMulai);
        $jamSelesai = $this->hitungJamSelesai($jamMulai, $sks);

        return Dosen::query()
            ->where('is_active', true)
            ->whereHas('klasters', fn ($query) => $query->whereKey($mataKuliah->klaster_id))
            ->whereDoesntHave('jadwals', fn ($query) => $this->batasiBentrok($query, $hari, $jamMulai, $jamSelesai, $semesterId, $abaikanJadwalId))
            ->orderBy('nama')
            ->get();
    }

    public function deteksiBentrok(array $data, ?int $abaikanJadwalId = null): array
    {
        $errors = [];
        $mataKuliah = MataKuliah::query()->with('klaster')->find($data['mata_kuliah_id'] ?? null);

        if (! $mataKuliah) {
            return $errors;
        }

        $hari = (string) $data['hari'];
        $semesterId = (int) $data['semester_id'];
        $jamMulai = $this->normalisasiJam((string) $data['jam_mulai']);
        $jamSelesai = $this->hitungJamSelesai($jamMulai, $mataKuliah->sks);

        $sesiWaktu = $this->sesiWaktuUntukJam($jamMulai);
        if (! $sesiWaktu) {
            $errors['jam_mulai'] = 'Jam tidak dalam sesi waktu (Pagi/Siang/Sore).';
        }

        return array_merge(
            $errors,
            $this->periksaRuangan($data, $hari, $jamMulai, $jamSelesai, $semesterId, $sesiWaktu, $abaikanJadwalId),
            $this->periksaDosen($data, $mataKuliah, $hari, $jamMulai, $jamSelesai, $semesterId, $abaikanJadwalId)
        );
    }

    private function periksaRuangan(array $data, string $hari, string $jamMulai, string $jamSelesai, int $semesterId, ?SesiWaktu $sesiWaktu, ?int $abaikanJadwalId): array
    {
        $ruangan = Ruangan::query()->with('sesiWaktus')->find($data['ruangan_id'] ?? null);
        if (! $ruangan) {
            return [];
        }

        if ($sesiWaktu && ! $ruangan->sesiWaktus->contains('id', $sesiWaktu->id)) {
            return ['ruangan_id' => "Ruangan {$ruangan->kode} di luar sesi {$sesiWaktu->nama}."];
        }

        $bentrok = Jadwal::with('mataKuliah')->where('ruangan_id', $ruangan->id)
            ->when($abaikanJadwalId, fn ($q) => $q->whereKeyNot($abaikanJadwalId))
            ->where(fn ($q) => $this->batasiBentrok($q, $hari, $jamMulai, $jamSelesai, $semesterId))
            ->first();

        return $bentrok ? ['ruangan_id' => "Ruangan terpakai: {$bentrok->mataKuliah->nama}."] : [];
    }

    private function periksaDosen(array $data, MataKuliah $mataKuliah, string $hari, string $jamMulai, string $jamSelesai, int $semesterId, ?int $abaikanJadwalId): array
    {
        $dosen = Dosen::query()->with('klasters')->find($data['dosen_id'] ?? null);
        if (! $dosen) {
            return [];
        }

        if (! $dosen->klasters->contains('id', $mataKuliah->klaster_id)) {
            return ['dosen_id' => "Dosen {$dosen->nama} bukan klaster {$mataKuliah->klaster?->nama}."];
        }

        $bentrok = Jadwal::with('mataKuliah')->where('dosen_id', $dosen->id)
            ->when($abaikanJadwalId, fn ($q) => $q->whereKeyNot($abaikanJadwalId))
            ->where(fn ($q) => $this->batasiBentrok($q, $hari, $jamMulai, $jamSelesai, $semesterId))
            ->first();

        return $bentrok ? ['dosen_id' => "Dosen sudah mengajar kelas {$bentrok->mataKuliah->nama} pada jam tersebut."] : [];
    }

    private function batasiBentrok(mixed $query, string $hari, string $jamMulai, string $jamSelesai, int $semesterId, ?int $abaikanJadwalId = null): mixed
    {
        return $query->where('semester_id', $semesterId)->where('hari', $hari)
            ->when($abaikanJadwalId, fn ($q) => $q->whereKeyNot($abaikanJadwalId))
            ->where('jam_mulai', '<', $jamSelesai)->where('jam_selesai', '>', $jamMulai);
    }

    public function ruanganTersedia(string $hari, string $jamMulai, int $sks, int $semesterId, ?int $abaikanJadwalId = null): Collection
    {
        $jM = $this->normalisasiJam($jamMulai);
        $jS = $this->hitungJamSelesai($jM, $sks);
        $sesi = $this->sesiWaktuUntukJam($jM);

        return Ruangan::query()->where('is_active', true)
            ->when($sesi, fn ($q) => $q->whereHas('sesiWaktus', fn ($q) => $q->whereKey($sesi->id)))
            ->whereDoesntHave('jadwals', fn ($q) => $this->batasiBentrok($q, $hari, $jM, $jS, $semesterId, $abaikanJadwalId))
            ->orderBy('kode')->get();
    }

    public function simpan(array $data): Jadwal
    {
        return DB::transaction(function () use ($data): Jadwal {
            $errors = $this->deteksiBentrok($data);
            if ($errors !== []) {
                throw ValidationException::withMessages($errors);
            }

            $mataKuliah = MataKuliah::query()->findOrFail($data['mata_kuliah_id']);
            $jM = $this->normalisasiJam((string) $data['jam_mulai']);

            return Jadwal::query()->create([
                'semester_id' => (int) $data['semester_id'],
                'mata_kuliah_id' => $mataKuliah->id,
                'dosen_id' => (int) $data['dosen_id'],
                'ruangan_id' => (int) $data['ruangan_id'],
                'hari' => (string) $data['hari'],
                'jam_mulai' => $jM,
                'jam_selesai' => $this->hitungJamSelesai($jM, $mataKuliah->sks),
                'kelas' => (string) $data['kelas'],
                'jumlah_mahasiswa' => (int) ($data['jumlah_mahasiswa'] ?? 0),
                'catatan' => $data['catatan'] ?? null,
            ]);
        });
    }

    public function perbarui(Jadwal $jadwal, array $data): Jadwal
    {
        return DB::transaction(function () use ($jadwal, $data): Jadwal {
            $errors = $this->deteksiBentrok($data, $jadwal->id);
            if ($errors !== []) {
                throw ValidationException::withMessages($errors);
            }

            $mataKuliah = MataKuliah::query()->findOrFail($data['mata_kuliah_id']);
            $jM = $this->normalisasiJam((string) $data['jam_mulai']);

            $jadwal->update([
                'semester_id' => (int) $data['semester_id'],
                'mata_kuliah_id' => $mataKuliah->id,
                'dosen_id' => (int) $data['dosen_id'],
                'ruangan_id' => (int) $data['ruangan_id'],
                'hari' => (string) $data['hari'],
                'jam_mulai' => $jM,
                'jam_selesai' => $this->hitungJamSelesai($jM, $mataKuliah->sks),
                'kelas' => (string) $data['kelas'],
                'jumlah_mahasiswa' => (int) ($data['jumlah_mahasiswa'] ?? 0),
                'catatan' => $data['catatan'] ?? null,
            ]);

            return $jadwal;
        });
    }
}
