<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Infrastructure\Security\AccessTokenChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AppTestCase extends WebTestCase
{
    protected static ?EntityManagerInterface $em = null;
    protected static ?KernelBrowser $client = null;

    public function setUp(): void
    {
        self::$client = static::createClient();
        self::$em = self::getContainer()->get(EntityManagerInterface::class);
        self::$em->getConnection()->setAutoCommit(false);
        self::$em->getConnection()->beginTransaction();
    }

    public function tearDown(): void
    {
        if (self::$em->getConnection()->isTransactionActive()) {
            self::$em->getConnection()->rollBack();
            self::$em->close();
            self::$em = null;
        }

        parent::tearDown();
    }

    protected static function sendGet(string $uri, array $parameters = []): Response
    {
        self::$client->request(
            'GET',
            $uri . '?' . http_build_query($parameters),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_' . AccessTokenChecker::HEADER_KEY => $_ENV['BACKEND_ACCESS_TOKEN']
            ]
        );

        return self::$client->getResponse();
    }
}
