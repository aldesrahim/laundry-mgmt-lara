<?php

namespace App\Services\ReportTemplate\Components;

class Notes extends Component
{
    protected string $view = 'report-templates.components.notes';

    public function __construct(
        protected array $notes = [],
    ) {
        $this->data = [
            'notes' => array_map(function ($note) {
                if ($note instanceof Component) {
                    return $note;
                }

                return new Text($note, ['text-center', 'mb-1']);
            }, $this->notes)
        ];
    }
}
