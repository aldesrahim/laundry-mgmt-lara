<?php

namespace App\Services\ReportTemplate\Components\Table;

class Summary
{
    public function __construct(
        protected string $title,
        protected array|string $value,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getValue(): array|string
    {
        return $this->value;
    }
}
