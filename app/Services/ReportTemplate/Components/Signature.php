<?php

namespace App\Services\ReportTemplate\Components;

class Signature extends Component
{
    protected string $view = 'report-templates.components.signature';

    public function __construct(
        protected string $location,
        protected string $name,
    ) {
        $this->data = [
            'location' => $this->location,
            'name' => $this->name,
        ];
    }
}
