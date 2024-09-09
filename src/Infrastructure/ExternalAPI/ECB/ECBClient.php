<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\ECB;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

readonly class ECBClient implements ECBClientInterface
{
    public function __construct(
        private string $baseUrl,
        private ClientInterface $client,
    ) {
    }

    /**
     * @throws ClientException
     * @throws RequestException|GuzzleException
     */
    public function sendGet(string $uri): ResponseInterface
    {
        $url = sprintf('%s%s', $this->baseUrl, $uri);

        return $this->client->request('GET', $url);
    }
}
