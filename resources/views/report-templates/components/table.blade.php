@php
    $columnCount = count($columns);
@endphp

<table class="table">
    <thead>
    <tr>
        @foreach($columns as $column)
            <th
                @if($column->getColspan()) colspan="{{ $column->getColspan() }}" @endif
            >
                <p class="subtitle text-center my-0">{{ $column->getTitle() }}</p>
            </th>
        @endforeach
    </tr>
    </thead>

    <tbody>
    @foreach($content as $row)
        <tr>
            @foreach($columns as $column)
                @php
                    $columnValue = match ($column::class) {
                        App\Services\ReportTemplate\Components\Table\IncrementColumn::class => $loop->parent->iteration,
                        default => data_get($row, $column->getColumn()),
                    };

                    if ($columnFormat = $column->getFormatUsing()) {
                        $columnValue = $columnFormat($columnValue, $row);
                    }
                @endphp
                <td
                    @if($column->getColspan()) colspan="{{ $column->getColspan() }}" @endif
                @if($column->getClasses()) class="{{ $column->getClasses() }}" @endif
                >
                    <p class="text-center my-0">{{ $columnValue }}</p>
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>

    @if(filled($summaries))
        @php
            $footerColspan = $columnCount > 2 ? $columnCount - 2 : null;
        @endphp

        <tfoot>
        @foreach($summaries as $summary)
            @php
                $summaryValue = \Illuminate\Support\Arr::wrap($summary->getValue());
                $summaryCount = count($summaryValue);

                $footerColspan -= ($summaryCount - 1)
            @endphp

            <tr>
                @if($summaryCount !== $columnCount)
                    <td>
                        <p class="subtitle my-0 text-center">{{ $summary->getTitle() }}</p>
                    </td>
                @endif

                @if($footerColspan > 0)
                    <td colspan="{{ $footerColspan }}">
                        &nbsp;
                    </td>
                @endif

                @foreach($summaryValue as $sumVal)
                    <td>
                        <p class="subtitle my-0 text-center">{{ $sumVal ?? '' }}</p>
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tfoot>
    @endif
</table>
