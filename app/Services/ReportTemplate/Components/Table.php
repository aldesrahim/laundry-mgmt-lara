<?php

namespace App\Services\ReportTemplate\Components;

use App\Services\ReportTemplate\Components\Table\Column;
use App\Services\ReportTemplate\Components\Table\Summary;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class Table extends Component
{
    protected string $view = 'report-templates.components.table';

    /**
     * @param array<Column> $columns
     * @param LazyCollection|Collection|array $content
     * @param array<Summary> $summaries
     */
    public function __construct(
        protected array                           $columns,
        protected LazyCollection|Collection|array $content,
        protected array                           $summaries = [],
    ) {
        $this->data = [
            'columns' => $this->columns,
            'content' => $this->content,
            'summaries' => $this->summaries,
            'rowSerializer' => $this->getRowSerializer(),
        ];
    }

    protected function getRowSerializer(): Closure
    {
        return function ($row) {
            if ($row instanceof Arrayable) {
                return $row->toArray();
            }

            return $row;
        };
    }
}
