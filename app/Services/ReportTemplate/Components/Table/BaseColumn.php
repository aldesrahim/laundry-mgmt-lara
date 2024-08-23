<?php

namespace App\Services\ReportTemplate\Components\Table;

use Closure;

abstract class BaseColumn
{
    protected ?int $colspan = null;
    protected ?string $classes = null;
    protected ?Closure $formatUsing = null;

    protected string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getColspan(): ?int
    {
        return $this->colspan;
    }

    public function setColspan(int $colspan): static
    {
        $this->colspan = $colspan;

        return $this;
    }

    public function getClasses(): ?int
    {
        return $this->colspan;
    }

    public function setClasses(int $classes): static
    {
        $this->classes = $classes;

        return $this;
    }

    public function formatUsing(Closure $formatUsing): static
    {
        $this->formatUsing = $formatUsing;

        return $this;
    }

    public function getFormatUsing(): ?Closure
    {
        return $this->formatUsing;
    }
}
