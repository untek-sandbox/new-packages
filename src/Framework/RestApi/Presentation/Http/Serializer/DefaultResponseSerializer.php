<?php

namespace Untek\Framework\RestApi\Presentation\Http\Serializer;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Untek\Component\Arr\Helpers\ArrayHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Model\DataProvider\Libs\DataProvider;
use Untek\Framework\Rpc\Domain\Model\RpcResponseEntity;

class DefaultResponseSerializer implements ResponseSerializerInterface
{

    private $attributesOnly;
    private $attributesExclude;

    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function setAttributesOnly(array $attributesOnly): void
    {
        $this->attributesOnly = $attributesOnly;
    }

    public function setAttributesExclude(array $attributesExclude): void
    {
        $this->attributesExclude = $attributesExclude;
    }

    public function encode($data): Response
    {
        if ($data instanceof Enumerable) {
            $result = $this->encodeCollection($data);
        } elseif ($data instanceof DataProvider) {
            $result = $this->encodeDataProvider($data);
        } elseif (is_object($data)) {
            $result = $this->encodeEntity($data);
        } elseif (is_array($data)) {
            $result = $this->encodeArray($data);
        } else {
            $result = $data;
        }
        return new JsonResponse($result);
    }

    protected function normalizers(): array
    {
        return [
            new DateTimeNormalizer(),
            new ObjectNormalizer(),
        ];
    }

    protected function encodeEntity(object $entity)
    {
        $array = $this->serializer->normalize($entity);
        $array = $this->filterEntityAttributes($array);
        return $array;
    }

    protected function encodeArray(array $entity)
    {
        $array = $this->serializer->normalize($entity);
        $array = $this->filterEntityAttributes($array);
        return $array;
    }

    protected function filterEntityAttributes(array $array): array
    {
        if ($this->attributesOnly) {
            $array = ArrayHelper::filter($array, $this->attributesOnly);
        }
        if ($this->attributesExclude) {
            foreach ($this->attributesExclude as $key) {
                ArrayHelper::removeItem($array, $key);
            }
        }
        return $array;
    }

    protected function encodeCollection(Enumerable $collection)
    {
        $array = [];
        foreach ($collection as $entity) {
            $array[] = $this->encodeEntity($entity);
        }
        return $array;
    }

    protected function encodeDataProvider(DataProvider $dataProvider)
    {
        $body = $this->encodeCollection($dataProvider->getCollection());
        $meta = $this->encodePaginate($dataProvider);
        $response = new RpcResponseEntity();
        $response->setResult($body);
        foreach ($meta as $metaKey => $metaValue) {
            $response->addMeta($metaKey, $metaValue);
        }
        return $response;
    }

    protected function encodePaginate(DataProvider $dataProvider)
    {
        $meta['perPage'] = $dataProvider->getPageSize();
        $meta['totalCount'] = $dataProvider->getTotalCount();
        $meta['page'] = $dataProvider->getPage();
        return $meta;
    }
}
