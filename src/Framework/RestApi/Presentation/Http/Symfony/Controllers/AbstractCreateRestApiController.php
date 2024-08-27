<?php

namespace Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Component\Code\Factories\PropertyAccess;
use Untek\Framework\RestApi\Infrastructure\Enums\RestApiHeaderEnum;

abstract class AbstractCreateRestApiController extends AbstractRestApiController
{

    protected string $routeName;
    protected UrlGeneratorInterface $urlGenerator;

    protected function createResponse(object $entity): JsonResponse
    {
        $data = $this->encodeObject($entity);
        $response = $this->serialize($data);

        $headers = [
            RestApiHeaderEnum::LOCATION => $this->generateLocationUri($entity),
        ];
        $response->headers->add($headers);
        $response->setStatusCode(201);

        return $response;
    }

    protected function generateLocationUri(object $entity): string
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $id = $propertyAccessor->getValue($entity, 'id');
        $parameters = [
            'id' => $id,
        ];
        return $this->urlGenerator->generate($this->routeName, $parameters);
    }
}