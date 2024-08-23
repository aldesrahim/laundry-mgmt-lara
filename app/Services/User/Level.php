<?php

namespace App\Services\User;

use Filament\Support\Contracts\HasLabel;

enum Level: string implements HasLabel
{
    case Administrator = 'super_admin';

    case Supervisor = 'supervisor';

    case Worker = 'worker';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Administrator => 'Administrator',
            self::Supervisor => 'Supervisor',
            self::Worker => 'Petugas',
        };
    }
}
