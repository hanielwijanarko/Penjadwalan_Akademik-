<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Klaster sesi waktu (Pagi / Siang / Sore) dengan rentang jamnya.
 *
 * Kolom jam disimpan sebagai string H:i agar kompatibel dengan kolom TIME MySQL.
 */
class SesiWaktu extends Model
{
    protected $table = 'sesi_waktus';

    protected $fillable = [
        'kode',
        'nama',
        'jam_mulai',
        'jam_selesai',
        'urutan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    protected function jamMulaiPendek(): Attribute
    {
        return Attribute::make(get: fn (): string => substr((string) $this->jam_mulai, 0, 5));
    }

    protected function jamSelesaiPendek(): Attribute
    {
        return Attribute::make(get: fn (): string => substr((string) $this->jam_selesai, 0, 5));
    }

    protected function rentangJam(): Attribute
    {
        return Attribute::make(get: fn (): string => $this->jam_mulai_pendek.' - '.$this->jam_selesai_pendek);
    }

    /** @return BelongsToMany<Ruangan, $this> */
    public function ruangans(): BelongsToMany
    {
        return $this->belongsToMany(Ruangan::class, 'ruangan_sesi_waktu')->withTimestamps();
    }
}
