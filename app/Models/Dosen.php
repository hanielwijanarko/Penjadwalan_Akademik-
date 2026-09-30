<?php

namespace App\Models;

use Database\Factories\DosenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $nidn
 * @property string $nama
 * @property string $email
 */
class Dosen extends Model
{
    /** @use HasFactory<DosenFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nidn',
        'nama',
        'email',
        'no_hp',
        'jabatan_fungsional',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<Jadwal, $this> */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    /** @return BelongsToMany<Klaster, $this> */
    public function klasters(): BelongsToMany
    {
        return $this->belongsToMany(Klaster::class, 'dosen_klaster')->withTimestamps();
    }
}
