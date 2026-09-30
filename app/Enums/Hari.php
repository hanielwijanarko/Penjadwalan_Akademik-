<?php

namespace App\Enums;

/**
 * Hari perkuliahan yang tersedia pada penjadwalan.
 */
enum Hari: string
{
    case Senin = 'senin';
    case Selasa = 'selasa';
    case Rabu = 'rabu';
    case Kamis = 'kamis';
    case Jumat = 'jumat';
    case Sabtu = 'sabtu';

    public function label(): string
    {
        return match ($this) {
            self::Senin => 'Senin',
            self::Selasa => 'Selasa',
            self::Rabu => 'Rabu',
            self::Kamis => 'Kamis',
            self::Jumat => 'Jumat',
            self::Sabtu => 'Sabtu',
        };
    }

    /**
     * Daftar nilai untuk keperluan validasi/opsi dropdown.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Daftar pasangan value => label untuk dropdown Blade.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $hari) => [$hari->value => $hari->label()])
            ->all();
    }
}
