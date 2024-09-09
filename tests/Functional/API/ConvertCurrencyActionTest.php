<?php

declare(strict_types=1);

namespace App\Tests\Functional\API;

use App\Tests\Functional\AppTestCase;
use Symfony\Component\HttpFoundation\Response;

class ConvertCurrencyActionTest extends AppTestCase
{
    public function testSuccess(): void
    {
        $requestData = [
            'from' => 'EUR',
            'to' => 'USD',
            'amount' => 100
        ];

        $response = static::sendGet('/api/currencies/convert', $requestData);
        $this->assertTrue($response->getStatusCode() === Response::HTTP_OK);

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('converted_amount', $data);
        $this->assertSame(90, $data['converted_amount']);
    }
}
