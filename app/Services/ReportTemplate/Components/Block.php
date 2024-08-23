<?php

namespace App\Services\ReportTemplate\Components;

class Block extends Component
{
    protected string $view = 'report-templates.components.block';

    /**
     * @param array<Component> $components
     */
    public function __construct(
        protected array $components = [],
        protected array $extraClasses = [],
        protected array $classes = ['row', 'justify-between'],
    ) {
        if (filled($this->extraClasses)) {
            $this->classes = array_merge($this->classes, $this->extraClasses);
        }

        $this->data = [
            'components' => $this->components,
            'classes' => implode(' ', $this->classes),
        ];
    }

    public function add(Component $component): static
    {
        $this->data['components'][] = $component;

        return $this;
    }
}
