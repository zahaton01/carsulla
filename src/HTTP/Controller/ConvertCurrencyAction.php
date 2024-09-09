<?php

declare(strict_types=1);

namespace App\HTTP\Controller;

use App\HTTP\Request\ConvertCurrencyRequest;
use App\Infrastructure\Service\CurrencyExchangeRate\Service\CurrencyConverterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/currencies/convert', name: 'api_convert_currencies')]
class ConvertCurrencyAction extends AbstractController
{
    public function __construct(
        private readonly CurrencyConverterInterface $currencyConverter
    ) {
    }

    public function __invoke(ConvertCurrencyRequest $request): JsonResponse
    {
        return $this->json([
            'converted_amount' => $this->currencyConverter->convert(
                $request->from,
                $request->to,
                $request->amount
            )
        ]);
    }
}
