<?php

namespace App\Services\ReportTemplate\Components\Header;

class Logo
{
    public function __construct(
        public ?string $logo = null,
        public int     $height = 64,
        public int     $maxWidth = 100,
    ) {
    }

    public function getStyle(): string
    {
        return "max-width: {$this->maxWidth}px; height: {$this->height}px;";
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }
}
