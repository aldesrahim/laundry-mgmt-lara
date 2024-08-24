<?php

namespace App\Services\ReportTemplate\Drivers;

use App\Models\Transaction;
use App\Services\ReportTemplate\Components\Block;
use App\Services\ReportTemplate\Components\Header;
use App\Services\ReportTemplate\Components\Table;
use App\Services\ReportTemplate\Components\Text;
use App\Services\ReportTemplate\ReportTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class ReportTransaction extends Driver
{
    public function mapPayload(array $payload): array
    {
        return [
            'from_date' => Carbon::parse($payload['from_date'])->toDateString(),
            'until_date' => Carbon::parse($payload['until_date'])->toDateString(),
        ];
    }


    public function getHeader(): Header
    {
        return new Header(
            'Laporan Transaksi - ' . config('app.name'),
        );
    }

    public function getTemplate(): ReportTemplate
    {
        return new ReportTemplate(
            header: $this->getHeader(),
            components: [
                ...($this->getFiltersBlock()),
                $this->getTableBlock(),
                $this->getTimestamp(),
                $this->getSignature(),
            ],
        );
    }

    public function getFiltersBlock(): array
    {
        return [
            new Block([
                new Text('Dari Tanggal:'),
                new Text($this->payload['from_date'])
            ]),
            new Block([
                new Text('Sampai Tanggal:'),
                new Text($this->payload['until_date'])
            ]),
        ];
    }

    public function getTableBlock(): Table
    {
        $query = static::getQuery($this->payload);
        $total = $query->clone()->sum('total');

        return new Table(
            columns: [
                new Table\IncrementColumn('#'),
                new Table\Column('Pelanggan', 'customer'),
                (new Table\Column('Berat (kg)', 'weight'))->formatUsing(fn ($value) => Number::format($value)),
                new Table\Column('Layanan', 'service.name'),
                new Table\Column('Tanggal Masuk', 'date'),
                new Table\Column('Masuk Antrean', 'queued_at'),
                new Table\Column('Tanggal Selesai', 'queue.finished_at'),
                (new Table\Column('Total', 'total'))->formatUsing(fn ($value) => Number::currency($value, 'idr')),
                new Table\Column('Dibuat Oleh', 'user.name'),
            ],
            content: $query->cursor(),
            summaries: [
                new Table\Summary('Total', [
                    Number::currency($total, 'idr'),
                    null,
                ])
            ],
        );
    }

    public static function getQuery(array $payload): Builder
    {
        $dates = [
            $payload['from_date'],
            $payload['until_date'],
        ];

        return Transaction::query()
            ->selectRaw('transactions.*')
            ->with(['service', 'user', 'queue'])
            ->leftJoin('queues', 'queues.transaction_id', 'transactions.id')
            ->where(
                fn (Builder $query) => $query
                    ->whereBetween(DB::raw('DATE(`transactions`.`date`)'), $dates)
                    ->orWhereBetween(DB::raw('DATE(`transactions`.`queued_at`)'), $dates)
                    ->orWhereBetween(DB::raw('DATE(`queues`.`finished_at`)'), $dates)
            )
            ->orderBy('transactions.date');
    }
}
