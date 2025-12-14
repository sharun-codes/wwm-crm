<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\CrmException;

class InvalidStageTransition extends CrmException
{
    public function userMessage(): string
    {
        return 'You cannot move this deal to the selected stage.';
    }
}
