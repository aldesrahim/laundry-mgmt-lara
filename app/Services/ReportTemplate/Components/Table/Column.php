<?php

namespace App\Services\ReportTemplate\Components\Table;

class Column extends BaseColumn
{
    public function __construct(
        protected string $title,
        protected string $column,
    ) {
    }

    public function getColumn(): string
    {
        return $this->column;
    }
}
