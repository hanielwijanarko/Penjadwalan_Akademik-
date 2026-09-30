<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Jadwal kuliah: satu matkul diampu satu dosen pada satu ruangan,
 * terikat ke satu semester, pada hari & rentang jam tertentu.
 *
 * Kolom `jam_mulai`/`jam_selesai` sengaja disimpan sebagai string berformat
 * H:i (bukan cast datetime) agar tetap kompatibel dengan kolom TIME MySQL.
 */
class Jadwal extends Model
{
    protected $fillable = [
        'semester_id',
        'mata_kuliah_id',
        'dosen_id',
        'ruangan_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kelas',
        'jumlah_mahasiswa',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_mahasiswa' => 'integer',
        ];
    }

    /**
     * Jam mulai tanpa detik, untuk ditampilkan di UI.
     */
    protected function jamMulaiPendek(): Attribute
    {
        return Attribute::make(get: fn (): string => substr((string) $this->jam_mulai, 0, 5));
    }

    /**
     * Jam selesai tanpa detik, untuk ditampilkan di UI.
     */
    protected function jamSelesaiPendek(): Attribute
    {
        return Attribute::make(get: fn (): string => substr((string) $this->jam_selesai, 0, 5));
    }

    /**
     * Label rentang jam, mis. "07:30 - 10:00".
     */
    protected function rentangJam(): Attribute
    {
        return Attribute::make(get: fn (): string => $this->jam_mulai_pendek.' - '.$this->jam_selesai_pendek);
    }

    /** @return BelongsTo<Semester, $this> */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /** @return BelongsTo<MataKuliah, $this> */
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /** @return BelongsTo<Dosen, $this> */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /** @return BelongsTo<Ruangan, $this> */
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    /**
     * Scope rentang jam yang tumpang tindih dengan [mulai, selesai).
     */
    public function scopeTumpangTindih(Builder $query, string $mulai, string $selesai): Builder
    {
        return $query->where('jam_mulai', '<', $selesai)
            ->where('jam_selesai', '>', $mulai);
    }
}
