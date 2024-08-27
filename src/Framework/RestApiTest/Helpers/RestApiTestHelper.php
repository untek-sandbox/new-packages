<?php

namespace Untek\Framework\RestApiTest\Helpers;

use Symfony\Component\HttpFoundation\Response;
use Untek\Component\Arr\Helpers\ArrayPathHelper;
use Untek\Develop\Debug\DataDumper;
use Yiisoft\Arrays\ArrayHelper;

class RestApiTestHelper
{

    public static function printResponseData(Response $response, ?string $path = null, ?string $format = 'php'): void
    {
        $data = RestApiTestHelper::extractData($response);
        if ($path) {
            $data = ArrayPathHelper::getValue($data, $path);
        }
        DataDumper::print($data, $format);
    }

    public static function extractHeaders(Response $response): array
    {
        $headers = [];
        foreach ($response->headers->allPreserveCase() as $name => $value) {
            $headers[$name] = $value[0];
        }
        return $headers;
    }

    public static function extractData(Response $response)
    {
        if ($response->headers->get('Content-Type') == 'application/json') {
            return json_decode($response->getContent(), true);
        }
        return $response->getContent();
    }

    public static function headersToServerParameters(array $headers): array
    {
        $server = [];
        if ($headers) {
            foreach ($headers as $key => $value) {
                $value = ArrayHelper::toArray($value);
                $key = strtoupper(str_replace('-', '_', $key));
                if (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'], true)) {
                    $server[$key] = implode(', ', $value);
                } else {
                    $server['HTTP_' . $key] = implode(', ', $value);
                }
            }
        }
        return $server;
    }
}
