<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\CrmException;

class DealStageBlockedException extends Exception
{
    public function __construct(
        public string $reason,
        public ?string $action = null
    ) {
        parent::__construct($reason);
    }
}
