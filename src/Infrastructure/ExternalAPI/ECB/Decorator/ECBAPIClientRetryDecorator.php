<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\ECB\Decorator;

use App\Infrastructure\ExternalAPI\ECB\ECBClientInterface;
use App\Infrastructure\ExternalAPI\ECB\Exception\ECBAPIException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ECBAPIClientRetryDecorator implements ECBClientInterface
{
    private const int MAX_RETRIES = 1;
    private const int SLEEP_BETWEEN_RETRIES = 1;

    private ECBClientInterface $decoratedClient;

    public function __construct(ECBClientInterface $decoratedClient)
    {
        $this->decoratedClient = $decoratedClient;
    }

    public function sendGet(string $uri): ResponseInterface
    {
        return $this->doWithRetry(function () use ($uri) {
            return $this->decoratedClient->sendGet($uri);
        });
    }

    private function doWithRetry(callable $callback)
    {
        $retryCounter = 0;

        $e = null;
        $response = null;
        while ($retryCounter < self::MAX_RETRIES) {
            try {
                return $callback();
            } catch (ClientException|GuzzleException $e) {
                if (method_exists($e, 'getResponse')) {
                    $response = $e->getResponse();

                    if (in_array($response->getStatusCode(), [400, 422])) {
                        break;
                    }
                }

                sleep(self::SLEEP_BETWEEN_RETRIES);
                $retryCounter++;
            } catch (Throwable $e) {
                sleep(self::SLEEP_BETWEEN_RETRIES);
                $retryCounter++;
            }
        }

        throw new ECBAPIException(
            sprintf(
                'ECB api request failed after %s retries. [%s]',
                self::MAX_RETRIES,
                $e ? $e->getMessage() : 'Unknown error',
            ),
            $response
        );
    }
}
