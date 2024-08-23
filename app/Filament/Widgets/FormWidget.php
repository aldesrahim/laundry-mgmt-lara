<?php

namespace App\Filament\Widgets;

use App\Filament\Standalone\StandaloneForm;
use App\Filament\Standalone\StandaloneEdit;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;

abstract class FormWidget extends Widget implements HasForms
{
    use StandaloneForm;
    use StandaloneEdit;

    protected static string $view = 'filament.widgets.form-widget';

    protected int|string|array $columnSpan = 'full';
}
