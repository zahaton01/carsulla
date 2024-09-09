<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\CBR\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;

class CBRAPIException extends Exception
{
    public function __construct(
        string $message,
        private readonly ?ResponseInterface $response
    ) {
        parent::__construct($message, 500);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
