<?php

namespace App\Infrastructure\ExternalAPI\ECB;

use Psr\Http\Message\ResponseInterface;

interface ECBClientInterface
{
    public function sendGet(string $uri): ResponseInterface;
}
