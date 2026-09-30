<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Mata kuliah. SKS menjadi dasar durasi (1 SKS = 50 menit).
 */
class MataKuliah extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'sks',
        'deskripsi',
        'sifat',
        'klaster_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sks' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Klaster, $this> */
    public function klaster(): BelongsTo
    {
        return $this->belongsTo(Klaster::class, 'klaster_id');
    }

    /** @return HasMany<Jadwal, $this> */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'mata_kuliah_id');
    }
}
