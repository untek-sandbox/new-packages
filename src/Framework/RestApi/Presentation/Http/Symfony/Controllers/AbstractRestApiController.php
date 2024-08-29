<?php

namespace Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Untek\Core\Instance\Helpers\MappingHelper;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Framework\RestApi\Presentation\Http\Serializer\DefaultResponseSerializer;
use Untek\Framework\RestApi\Presentation\Http\Serializer\ResponseSerializerInterface;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Helpers\RestApiHelper;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Interfaces\RestApiSchemaInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Model\Validator\ObjectValidator;

/**
 * @property RestApiSchemaInterface $schema
 * @property ObjectValidator $objectValidator
 */
abstract class AbstractRestApiController
{

//    protected RestApiSchemaInterface $schema;

    protected function getSchema(): ?RestApiSchemaInterface
    {
        if(isset($this->schema)) {
            return $this->schema;
        }
    }

    protected function checkSchema()
    {
        return;

        if (!isset($this->schema)) {
            throw new \RuntimeException('REST API schema not defined.');
        }
    }

    protected function encodeObject(mixed $data): array
    {
        $this->checkSchema();
        if (isset($this->schema)) {
            return $this->schema->encode($data);
        }
        return PropertyHelper::toArray($data);
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
    
    protected function validate(object $object) {
        if(isset($this->objectValidator)) {
            $violations = $this->objectValidator->validate($object);
            if ($violations->count()) {
                $exception = new UnprocessableEntityException();
                $exception->setViolations($violations);
                throw $exception;
            }
        }
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
        $normalizedData = $this->getSerializer()->normalize($data);
        return new JsonResponse($normalizedData);
    }

    private function getResponseSerializer(): ResponseSerializerInterface
    {
        $serializer = $this->getSerializer();
        return new DefaultResponseSerializer($serializer);
    }

    protected function getSerializer(): SerializerInterface
    {
        $encoders = [
            new XmlEncoder(),
            new JsonEncoder(),
        ];
        $normalizers = [
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(),
//            new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter()),
        ];
        return new Serializer($normalizers, $encoders);
    }
}
