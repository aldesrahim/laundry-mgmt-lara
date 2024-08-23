<?php

namespace App\Services\ReportTemplate\Components;

class Text extends Component
{
    protected string $view = 'report-templates.components.text';

    public function __construct(
        protected ?string $text = '',
        protected array   $classes = ['my-0.5'],
    ) {
        $this->data = [
            'text' => $this->text,
            'classes' => implode(' ', $this->classes),
        ];
    }
}
