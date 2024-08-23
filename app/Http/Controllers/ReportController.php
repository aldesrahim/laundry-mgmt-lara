<?php

namespace App\Http\Controllers;

use App\Services\ReportTemplate\Drivers\Driver;
use App\Services\ReportTemplate\Drivers\ReportQueue;
use App\Services\ReportTemplate\Drivers\ReportTransaction;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $driver)
    {
        /** @var class-string<Driver> $className */
        $className = match ($driver) {
            'queue' => ReportQueue::class,
            'transaction' => ReportTransaction::class,
            default => throw new InvalidArgumentException('Invalid driver'),
        };

        return (new $className())
            ->setPayload($request->all())
            ->render();
    }
}
