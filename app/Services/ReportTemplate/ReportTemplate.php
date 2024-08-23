<?php

namespace App\Services\ReportTemplate;

use App\Services\ReportTemplate\Components\Component;
use App\Services\ReportTemplate\Components\Header;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\View\View;
use Throwable;

class ReportTemplate implements Htmlable
{
    use Conditionable;

    /**
     * @param Header $header
     * @param array<Component> $components
     */
    public function __construct(
        protected Header $header,
        protected array  $components = [],
    ) {
    }

    public static function make(Header $header, array $components = []): static
    {
        return new static($header, $components);
    }

    public function add(Component $component): static
    {
        $this->components[] = $component;

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function toHtml(): string
    {
        return $this->getView()->render();
    }

    public function getView(): View
    {
        return view('report-templates.layout', [
            'header' => $this->header,
            'components' => $this->components,
        ]);
    }
}
