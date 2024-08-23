<?php

use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('receipt/{transaction}', ReceiptController::class)->name('receipt');
Route::get('report/{driver}', ReportController::class)->name('report');
