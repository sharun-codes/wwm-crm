<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\CrmException;

class DealValueRequired extends CrmException
{
    public function userMessage(): string
    {
        return 'Please enter a deal value before marking it as won.';
    }
}
