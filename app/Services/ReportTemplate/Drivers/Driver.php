<?php

namespace App\Services\ReportTemplate\Drivers;

use App\Services\ReportTemplate\Components\Header;
use App\Services\ReportTemplate\Components\Signature;
use App\Services\ReportTemplate\Components\Text;
use App\Services\ReportTemplate\ReportTemplate;
use Illuminate\Contracts\View\View;

abstract class Driver
{
    protected array $payload = [];

    public function setPayload(array $payload = []): static
    {
        $this->payload = $this->mapPayload($payload);

        return $this;
    }

    public function mapPayload(array $payload): array
    {
        return $payload;
    }

    abstract public function getHeader(): Header;

    abstract public function getTemplate(): ReportTemplate;

    public function render(): View
    {
        return $this->getTemplate()->getView();
    }

    public function getSignature(): Signature
    {
        return new Signature(
            'Jakarta, ' . now()->isoFormat('DD MMMM YYYY'),
            auth()->user()?->name ?? 'Sistem',
        );
    }

    public function getTimestamp(): Text
    {
        return new Text('Tanggal Cetak: ' . now()->toDateTimeString(), ['my-0.5', 'text-right']);
    }
}
