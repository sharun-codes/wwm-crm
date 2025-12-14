<?php

namespace App\Exceptions;

use RuntimeException;

abstract class CrmException extends RuntimeException
{
    abstract public function userMessage(): string;
}
