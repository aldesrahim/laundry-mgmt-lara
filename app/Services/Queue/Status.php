<?php

namespace App\Services\Queue;

use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasLabel
{
    case All = 'all';

    case Finished = 'finished';

    case NotFinished = 'not_finished';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::All => 'Semua',
            self::Finished => 'Sudah Selesai',
            self::NotFinished => 'Belum Selesai',
        };
    }
}
