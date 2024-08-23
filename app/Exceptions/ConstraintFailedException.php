<?php

namespace App\Exceptions;

use Exception;

class ConstraintFailedException extends Exception
{
    protected $message = 'Tidak dapat menghapus data ini, karena sudah terikat dengan data lain';
}
