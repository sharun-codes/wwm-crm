<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\CrmException;

class MissingRequirement extends CrmException
{
    public function __construct(
        string $message = 'data missing',
    ) {
        parent::__construct($message);
    }

    public function userMessage(): string
    {
        return "{$this->message}";
    }
}
