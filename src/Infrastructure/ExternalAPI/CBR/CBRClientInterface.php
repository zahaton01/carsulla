<?php

namespace App\Infrastructure\ExternalAPI\CBR;

use Psr\Http\Message\ResponseInterface;

interface CBRClientInterface
{
    public function sendGet(string $uri): ResponseInterface;
}
