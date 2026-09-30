<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Semester akademik. Hanya satu yang `is_active`.
 */
class Semester extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'tahun_ajaran',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<Jadwal, $this> */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    /** Scope: semester yang sedang berjalan. */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
