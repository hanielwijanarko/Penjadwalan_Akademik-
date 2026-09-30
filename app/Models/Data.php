<?php

namespace App\Models;

use Database\Factories\DataFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Contoh data yang dikelola melalui panel dengan pembatasan role.
 */
class Data extends Model
{
    /** @use HasFactory<DataFactory> */
    use HasFactory;

    protected $table = 'data';

    protected $fillable = [
        'title',
        'description',
        'created_by',
    ];

    /**
     * Pemilik data (user yang membuatnya).
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
