<?php

namespace App\Services\ReportTemplate\Drivers;

use App\Models\Queue;
use App\Services\Queue\Status;
use App\Services\ReportTemplate\Components\Block;
use App\Services\ReportTemplate\Components\Header;
use App\Services\ReportTemplate\Components\Table;
use App\Services\ReportTemplate\Components\Text;
use App\Services\ReportTemplate\ReportTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class ReportQueue extends Driver
{
    public function mapPayload(array $payload): array
    {
        return [
            'from_date' => Carbon::parse($payload['from_date'])->toDateString(),
            'until_date' => Carbon::parse($payload['until_date'])->toDateString(),
            'status' => Status::from($payload['status']),
        ];
    }


    public function getHeader(): Header
    {
        return new Header(
            'Laporan Antrean - ' . config('app.name'),
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
            new Block([
                new Text('Status:'),
                new Text($this->payload['status']->getLabel())
            ]),
        ];
    }

    public function getTableBlock(): Table
    {
        return new Table(
            columns: [
                new Table\IncrementColumn('#'),
                new Table\Column('Pelanggan', 'transaction.customer'),
                (new Table\Column('Berat (kg)', 'transaction.weight'))->formatUsing(fn ($value) => Number::format($value)),
                new Table\Column('Layanan', 'transaction.service.name'),
                new Table\Column('Tanggal Masuk', 'transaction.date'),
                new Table\Column('Masuk Antrean', 'transaction.queued_at'),
                new Table\Column('Tanggal Selesai', 'finished_at'),
                new Table\Column('Batch', 'batch'),
                new Table\Column('Urutan', 'order'),
                new Table\Column('Dibuat Oleh', 'user.name'),
            ],
            content: static::getQuery($this->payload)->cursor(),
        );
    }

    public static function getQuery(array $payload): Builder
    {
        $dates = [
            $payload['from_date'],
            $payload['until_date'],
        ];

        return Queue::query()
            ->selectRaw('queues.*')
            ->with(['transaction.service', 'user'])
            ->join('transactions', 'transactions.id', 'queues.transaction_id')
            ->when(
                $payload['status'] !== Status::All,
                fn (Builder $query) => $query->where('finished', $payload['status'] === Status::Finished)
            )
            ->where(
                fn (Builder $query) => $query
                    ->whereBetween(DB::raw('DATE(`transactions`.`date`)'), $dates)
                    ->orWhereBetween(DB::raw('DATE(`transactions`.`queued_at`)'), $dates)
                    ->orWhereBetween(DB::raw('DATE(`queues`.`finished_at`)'), $dates)
            )
            ->orderBy('batch')
            ->orderBy('order');
    }
}
