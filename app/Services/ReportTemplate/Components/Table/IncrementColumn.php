<?php

namespace App\Services\ReportTemplate\Components\Table;

class IncrementColumn extends BaseColumn
{
    public function __construct(
        protected string $title,
    ) {
    }
}
