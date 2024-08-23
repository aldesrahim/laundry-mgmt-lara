<?php

namespace App\Exceptions;

use Exception;

class NoPendingTransactionsException extends Exception
{
    protected $message = 'Tidak ada transaksi yang belum dimasukkan kedalam antrean';
}
