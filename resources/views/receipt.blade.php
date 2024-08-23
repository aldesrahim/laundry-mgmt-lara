<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css'])
</head>
<body>
<div class="bg-white px-6 py-8 max-w-sm">
    <div class="mb-6">
        <h1 class="text-lg font-bold">Transaksi #{{ $transaction->id }}</h1>
        <div class="text-gray-700">
            <div>Tanggal: {{ $transaction->date->toDateTimeString() }}</div>
        </div>
    </div>
    <div class="mb-8">
        <div class="mb-2">
            <div class="text-md font-bold">Pelanggan</div>
            <div class="text-gray-700">{{ $transaction->customer }}</div>
        </div>
        <div class="mb-2">
            <div class="text-md font-bold">Target Selesai</div>
            <div class="text-gray-700">{{ $transaction->target_date->toDateString() }}</div>
        </div>
        <div class="mb-2">
            <div class="text-md font-bold">Petugas</div>
            <div class="text-gray-700">{{ $transaction->user->name }}</div>
        </div>
    </div>
    <table class="w-full mb-8">
        <thead>
        <tr>
            <th class="text-left font-bold text-gray-700">Layanan</th>
            <th class="text-right font-bold text-gray-700">Berat (kg)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-left text-gray-700">{{ $transaction->service->name }}</td>
            <td class="text-right text-gray-700">{{ Number::format($transaction->weight) }}</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-left font-bold text-gray-700">Total</td>
            <td class="text-right font-bold text-gray-700">{{ Number::currency($transaction->total, 'idr', config('app.locale')) }}</td>
        </tr>
        </tfoot>
    </table>
    <div class="text-gray-700 mb-2 text-center">Terima kasih atas kepercayaan Anda!</div>
    <div class="text-gray-700 mb-2 text-center">{{ config('app.name') }}</div>
</div>
</body>
</html>
