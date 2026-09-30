<?php

namespace App\Models;

use Database\Factories\RuanganFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ruangan kelas beserta klaster sesi waktu yang diizinkan.
 */
class Ruangan extends Model
{
    /** @use HasFactory<RuanganFactory> */
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'kapasitas',
        'gedung',
        'lantai',
        'fasilitas',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fasilitas' => 'array',
            'is_active' => 'boolean',
            'kapasitas' => 'integer',
        ];
    }

    /**
     * Fasilitas selalu dinormalkan menjadi array, termasuk untuk data lama
     * yang tersimpan sebagai teks dipisah koma.
     *
     * @return Attribute<array<int, string>, never>
     */
    protected function fasilitasList(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes): array {
                $fasilitas = $attributes['fasilitas'] ?? null;

                if (is_string($fasilitas)) {
                    $fasilitas = json_decode($fasilitas, true);
                }

                if (is_string($fasilitas)) {
                    $fasilitas = explode(',', $fasilitas);
                }

                if (! is_array($fasilitas)) {
                    return [];
                }

                return array_values(array_filter(
                    array_map(fn (mixed $item): string => trim((string) $item), $fasilitas),
                    fn (string $item): bool => $item !== '',
                ));
            },
        );
    }

    /** Fasilitas dalam bentuk teks dipisah koma untuk ditampilkan. */
    protected function fasilitasTeks(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => implode(', ', $this->fasilitas_list),
        );
    }

    /** @return BelongsToMany<SesiWaktu, $this> */
    public function sesiWaktus(): BelongsToMany
    {
        return $this->belongsToMany(SesiWaktu::class, 'ruangan_sesi_waktu')->withTimestamps();
    }

    /** @return HasMany<Jadwal, $this> */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
}
