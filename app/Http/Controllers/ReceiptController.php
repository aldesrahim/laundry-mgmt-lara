<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Transaction $transaction)
    {
        $transaction->loadMissing(['user', 'service']);

        return view('receipt', compact('transaction'));
    }
}
