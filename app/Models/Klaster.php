<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Klaster keahlian / rumpun ilmu.
 */
class Klaster extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<Dosen, $this> */
    public function dosens(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }

    /** @return BelongsToMany<Dosen, $this> */
    public function anggotaDosen(): BelongsToMany
    {
        return $this->belongsToMany(Dosen::class, 'dosen_klaster')->withTimestamps();
    }

    /** @return HasMany<MataKuliah, $this> */
    public function mataKuliahs(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'klaster_id');
    }
}
