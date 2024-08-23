<?php

namespace App\Services\ReportTemplate\Components;

use App\Services\ReportTemplate\Components\Header\Logo;

class Header extends Component
{
    protected string $view = 'report-templates.components.header';

    public function __construct(
        public string $title,
        public array  $subtitles = [],
        public ?Logo  $logo = null,
    ) {
        $this->data = [
            'title' => $this->title,
            'subtitles' => $this->subtitles,
            'logo' => $this->logo,
        ];
    }
}
