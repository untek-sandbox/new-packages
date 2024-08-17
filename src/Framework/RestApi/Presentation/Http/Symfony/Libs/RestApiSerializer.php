<?php

namespace Untek\Framework\RestApi\Presentation\Http\Symfony\Libs;

use ArrayObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Untek\Core\Contract\Common\Exceptions\NotImplementedMethodException;
use Untek\Framework\RestApi\Presentation\Http\Serializer\DefaultResponseSerializer;
use Untek\Framework\RestApi\Presentation\Http\Serializer\ResponseSerializerInterface;

class RestApiSerializer implements SerializerInterface, DecoderInterface, DenormalizerInterface, NormalizerInterface
{

    private ResponseSerializerInterface $serializer;

    public function __construct()
    {
        $this->serializer = $this->getResponseSerializer();
    }

    public function getSupportedTypes(?string $format): array
    {
        throw new NotImplementedMethodException();
    }

    public function encode($data): Response
    {
        return $this->serializer->encode($data);
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
            new ObjectNormalizer(),
//            new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter()),
        ];
        return new Serializer($normalizers, $encoders);
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        $serializer = $this->getSerializer();
        return $serializer->serialize($data, $format, $context);
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        $serializer = $this->getSerializer();
        return $serializer->deserialize($data, $type, $format, $context);
    }

    public function decode(string $data, string $format, array $context = []): mixed
    {
        $serializer = $this->getSerializer();
        return $serializer->decode($data, $format, $context);
    }

    public function supportsDecoding(string $format): bool
    {
        $serializer = $this->getSerializer();
        return $serializer->supportsDecoding($format);
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $serializer = $this->getSerializer();
        return $serializer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = [] ): bool
    {
        $serializer = $this->getSerializer();
        return $serializer->supportsDenormalization($data, $type, $format);
    }

    public function normalize(mixed $object, string $format = null, array $context = []): float|array|ArrayObject|bool|int|string|null
    {
        $serializer = $this->getSerializer();
        return $serializer->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = [] ): bool
    {
        $serializer = $this->getSerializer();
        return $serializer->supportsNormalization($data, $format);
    }
}