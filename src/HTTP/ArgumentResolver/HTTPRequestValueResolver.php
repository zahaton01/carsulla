<?php

declare(strict_types=1);

namespace App\HTTP\ArgumentResolver;

use App\HTTP\Request\HTTPRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class HTTPRequestValueResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private LoggerInterface $logger
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!is_subclass_of($argument->getType(), HTTPRequestInterface::class)) {
            return [];
        }

        try {
            if ($this->emptyContentAllowed($request)) {
                $data = $this->serializer->serialize($request->query->all(), 'json');
                $context = [
                    AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                ];
            } else {
                $data = $request->getContent();
            }

            $this->logger->debug('Json request resolver. Request: ' . $data);
            $object = $this->serializer->deserialize($data, $argument->getType(), 'json', $context ?? []);
        } catch (NotNormalizableValueException $e) {
            $pattern = '/for class "[\w\\\]*"/i';

            if (preg_match($pattern, $e->getMessage())) {
                $msg = preg_replace('/for class "[\w\\\]*"/i', '', $e->getMessage());
                throw new BadRequestHttpException($msg);
            }

            $errorPayload = [
                'class' => $argument->getType(),
                'message' => $e->getMessage(),
                'expectedTypes' => $e->getExpectedTypes(),
                'currentType' => $e->getCurrentType(),
                'path' => $e->getPath(),
                'data' => $request->getContent(),
                'e' => $e,
            ];

            $this->logger->critical($e->getMessage(), $errorPayload);
            throw new BadRequestHttpException('Some properties have invalid value type');
        } catch (ExceptionInterface $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        yield $object;
    }

    private function emptyContentAllowed(Request $request): bool
    {
        return $request->isMethod(Request::METHOD_GET)
            || $request->isMethod(Request::METHOD_DELETE);
    }
}
