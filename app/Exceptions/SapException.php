<?php

namespace App\Exceptions;

use Exception;

class SapException extends Exception
{
    public function __construct(
        string $message = 'SAP request failed',
        protected int $statusCode = 500,
        protected array $details = []
    ) {
        parent::__construct($message, $statusCode);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}