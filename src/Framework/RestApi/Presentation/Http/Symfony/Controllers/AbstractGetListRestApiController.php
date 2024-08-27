<?php

namespace Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Untek\Framework\RestApi\Infrastructure\Enums\RestApiHeaderEnum;
use Untek\Model\DataProvider\Dto\CollectionData;

abstract class AbstractGetListRestApiController extends AbstractRestApiController
{

    protected function createResponse(CollectionData $collectionData): JsonResponse
    {
        $collection = $collectionData->getCollection();
        $list = $this->encodeList($collection);
        $response = $this->serialize($list);
        $page = $collectionData->getPage();
        $response->headers->add([
            RestApiHeaderEnum::CURRENT_PAGE => $page->getPageNumber(),
            RestApiHeaderEnum::PER_PAGE => $page->getPageSize(),
            RestApiHeaderEnum::TOTAL_COUNT => $page->getItemsTotalCount(),
            RestApiHeaderEnum::PAGE_COUNT => $page->getPageCount(),
        ]);
        return $response;
    }
}