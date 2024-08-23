<?php

namespace App\Services\ReportTemplate\Components;

class QRCode extends Component
{
    protected string $view = 'report-templates.components.qr-code';

    public function __construct(
        protected string $content,
        protected int    $size = 64,
    ) {
        $this->data = [
            'content' => urlencode($this->content),
            'size' => $this->size,
        ];
    }
}
