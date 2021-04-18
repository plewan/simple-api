<?php

namespace App\Exception;

use Exception;

class ClientException extends Exception
{
    public function __construct(int $code, ?string $error_message = null)
    {
        parent::__construct($error_message ?? 'Unknown Client Exception', $code);
    }
}