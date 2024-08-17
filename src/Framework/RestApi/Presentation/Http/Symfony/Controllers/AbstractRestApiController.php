<?php

namespace Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Untek\Core\Instance\Helpers\MappingHelper;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Framework\RestApi\Presentation\Http\Serializer\DefaultResponseSerializer;
use Untek\Framework\RestApi\Presentation\Http\Serializer\ResponseSerializerInterface;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Helpers\RestApiHelper;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Interfaces\RestApiSchemaInterface;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Libs\RestApiSerializer;

abstract class AbstractRestApiController
{

    protected RestApiSchemaInterface $schema;

    protected function checkSchema()
    {
        if (!isset($this->schema) && getenv('REST_API_SCHEMA_STRICT')) {
            throw new \RuntimeException('REST API schema not defined.');
        }
    }

    protected function encodeObject(mixed $data): array
    {
        $this->checkSchema();
        if (!isset($this->schema)) {
            return PropertyHelper::toArray($data);
        }
        return $this->schema->encode($data);
    }

    protected function encodeList(array $data): array
    {
        $list = [];
        foreach ($data as $entity) {
            $list[] = $this->encodeObject($entity);
        }
        return $list;
    }

    protected function extractData(Request $request): array
    {
        $format = RestApiHelper::getFormat($request);
        if ($request->getContent() && $format !== RestApiHelper::FORM_URLENCODED) {
            $content = $request->getContent();
            if (is_string($content) && $format) {
                $data = $this->getSerializer()->decode($content, $format);
            }
        } else {
            $data = $request->request->all();
        }
        if (empty($data)) {
            $data = $request->query->all();
        }
        return $data;
    }

    protected function createForm(Request $request, string $type): object
    {
        $data = $this->extractData($request);
        return MappingHelper::restoreObject($data, $type);
//        return $this->getSerializer()->denormalize($data, $type);
    }

    protected function error(string $message, int $statusCode = 500): JsonResponse
    {
        $data = [
            'message' => $message
        ];
        return new JsonResponse($data, $statusCode);
    }

    protected function emptyResponse(): JsonResponse
    {
        return new JsonResponse(null, 204);
    }

    protected function serialize($data): JsonResponse
    {
        if ($data === null) {
            return $this->emptyResponse();
        }
        /** @var JsonResponse $response */
        $this->checkSchema();
        $response = $this->getResponseSerializer()->encode($data);
        return $response;
    }

    private function getResponseSerializer(): ResponseSerializerInterface
    {
        $serializer = $this->getSerializer();
        return new DefaultResponseSerializer($serializer);
    }

    protected function getSerializer(): SerializerInterface
    {
        return new RestApiSerializer();
    }
}
