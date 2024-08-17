<?php

namespace Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Untek\Component\Http\Enums\HttpHeaderEnum;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers\AbstractRestApiController;
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
            HttpHeaderEnum::CURRENT_PAGE => $page->getPageNumber(),
            HttpHeaderEnum::PER_PAGE => $page->getPageSize(),
            HttpHeaderEnum::TOTAL_COUNT => $page->getItemsTotalCount(),
            HttpHeaderEnum::PAGE_COUNT => $page->getPageCount(),
        ]);
        return $response;
    }
}