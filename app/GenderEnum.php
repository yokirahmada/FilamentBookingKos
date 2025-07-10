<?php

namespace App;
use Filament\Support\Contracts\HasLabel;

// MODIFIKASI: Tambahkan ": string" dan implementasi HasLabel
enum GenderEnum: string implements HasLabel 
{
    // MODIFIKASI: Tambahkan nilai string untuk setiap case
    case PRIA = 'Pria';
    case WANITA = 'Wanita';
    case CAMPUR = 'Campur';

    // Fungsi ini tetap sama, untuk menampilkan label yang rapi
    public function getLabel(): ?string
    {
        return match ($this) {
            self::PRIA => 'Pria',
            self::WANITA => 'Wanita',
            self::CAMPUR => 'Campur',
        };
    }
}