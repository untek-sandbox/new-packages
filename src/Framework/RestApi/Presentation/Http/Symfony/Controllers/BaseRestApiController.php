<?php

namespace Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers;

use Untek\Framework\RestApi\Presentation\Http\Serializer\DefaultResponseSerializer;
use Untek\Framework\RestApi\Presentation\Http\Serializer\ResponseSerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Untek\Component\Arr\Helpers\ArrayHelper;

abstract class BaseRestApiController
{

    public function extractHeaders(array $headers): array
    {
        $result = [];
        foreach ($headers as $headerKey => $headerValues) {
            $headerKey = self::prepareHeaderKey($headerKey);
            $result[$headerKey] = ArrayHelper::first($headerValues);
        }
        return $result;
    }

    protected static function keyToServerVar(string $name): string
    {
        return strtr(mb_strtoupper($name), '-', '_');
    }

    protected static function prepareHeaderKey(string $name): string
    {
        return strtr(mb_strtolower($name), '_', '-');
    }

    protected function getFormat(Request $request): ?string
    {
        $format = null;
        $mimeType = $request->headers->get('Content-Type');
        if ($mimeType) {
            $extensions = (new MimeTypes)->getExtensions($mimeType);
            $format = ArrayHelper::first($extensions);
        }
        return $format;
    }

    protected function extractData(Request $request): array
    {
        $format = $this->getFormat($request);
        if ($request->getContent()) {
            $data = $request->getContent();
        } else {
            $data = $request->request->all();
        }
        if (is_string($data) && $format) {
            $data = $this->getSerializer()->decode($data, $format);
        }
        return $data;
    }

    protected function createForm(Request $request, string $type): object
    {
        $data = $this->extractData($request);
        return $this->getSerializer()->denormalize($data, $type);
    }

    protected function error(string $message, int $statusCode = 500): JsonResponse
    {
        $data = [
            'message' => $message
        ];
        return new JsonResponse($data, $statusCode);
    }

    protected function serialize($data): JsonResponse
    {
        return $this->getResponseSerializer()->encode($data);
    }

    protected function getResponseSerializer(): ResponseSerializerInterface
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
            new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter()),
        ];
        return new Serializer($normalizers, $encoders);
    }
}
