<?php

namespace Untek\Framework\RestApi\Presentation\Http\Symfony\Helpers;

use Symfony\Component\HttpFoundation\Request;
use Untek\Component\Code\Helpers\DeprecateHelper;
use Untek\Core\Instance\Helpers\MappingHelper;
use Untek\Model\DataProvider\Dto\PageRequest;
use Untek\Model\DataProvider\Interfaces\ExpandQueryInterface;
use Untek\Model\DataProvider\Interfaces\FilterQueryInterface;
use Untek\Model\DataProvider\Interfaces\PageQueryInterface;
use Untek\Model\DataProvider\Interfaces\SortQueryInterface;
use Untek\Model\DataProvider\Interfaces\FilterLanguageInterface;

class QueryParameterHelper
{

    public static function removeEmptyFilters(object $query): void
    {
        $filter = $query->getFilter();
        if($filter) {
            foreach ($filter as $name => $value) {
                if(empty($value)) {
                    unset($filter[$name]);
                }
            }
        }
        $query->setFilter($filter);
    }

    public static function fillQueryFromRequest(Request $request, object $query, int $defaultPageSize = 10): void
    {
        $queryParams = $request->query->all();

        if ($query instanceof SortQueryInterface) {
            $sort = $queryParams['sort'] ?? [];
            if (!empty($sort)) {
                $sortParams = QueryParameterHelper::extractSort($sort);
                $query->setSort($sortParams);
            }
        }

        if ($query instanceof ExpandQueryInterface) {
            $expand = $queryParams['expand'] ?? [];
            if (!empty($expand)) {
                $expandParams = QueryParameterHelper::extractExpand($expand);
                $query->setExpand($expandParams);
            }
        }

        if ($query instanceof FilterQueryInterface) {
            $filter = $queryParams['filter'] ?? [];
            if (!empty($filter)) {
                /** @var array $filter */
                $query->setFilter($filter);
            }
        }

        if ($query instanceof PageQueryInterface) {
            $page = $queryParams['page'] ?? [];
            $pageDto = self::extractPage($page, $defaultPageSize);
            $query->setPage($pageDto);
        }

        if ($query instanceof FilterLanguageInterface) {
            // todo: move to out getenv('DEFAULT_LANGUAGE')
            $language = $request->headers->get('Language', getenv('DEFAULT_LANGUAGE'));
            if (!empty($language) && $language != "") {
                $query->setLanguage($language);
            }
        }
    }

    /**
     * @param string|null $language
     * @param object $query
     * @deprecated
     */
    public static function fillLanguageQuery(?string $language, object $query): void
    {
        if (!empty($language) && $language != "") {
            $query->setLanguage($language);
        }
    }

    public static function extractExpand(mixed $expand): array
    {
        if (is_array($expand)) {
            $expandParams = $expand;
        } else {
            $expandParams = self::extractList($expand);
        }
        return $expandParams;
    }

    public static function extractPage(mixed $page, int $defaultPageSize = 10): PageRequest
    {
        if(!isset($page['size'])) {
            $page['size'] = $defaultPageSize;
        }
        /** @var PageRequest $pageDto */
        $pageDto = MappingHelper::restoreObject($page, PageRequest::class);
        return $pageDto;
    }

    public static function extractSort(mixed $sort): array
    {
        $sortParams = [];
        if (!empty($sort)) {
            if (is_array($sort)) {
                foreach ($sort as $sortField => $sortDirection) {
                    $sortParams[$sortField] = mb_strtolower($sortDirection) == 'asc' ? SORT_ASC : SORT_DESC;
                }
            } else {
                $sortParts = self::extractList($sort);
                foreach ($sortParts as $sortPart) {
                    $sortParamName = trim($sortPart, "\ -\t\n\r\0\x0B");
                    if ($sortPart[0] == '-') {
                        $sortParams[$sortParamName] = SORT_DESC;
                    } else {
                        $sortParams[$sortParamName] = SORT_ASC;
                    }
                }
            }
        }
        return $sortParams;
    }

    protected static function extractList(mixed $value): array
    {
        $params = [];
        if (!empty($value)) {
            $parts = explode(',', $value);
            foreach ($parts as $part) {
                $params[] = trim($part);
            }
        }
        return $params;
    }
}