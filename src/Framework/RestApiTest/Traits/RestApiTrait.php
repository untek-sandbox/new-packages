<?php

namespace Untek\Framework\RestApiTest\Traits;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Untek\Framework\RestApiTest\Helpers\RestApiTestHelper;

trait RestApiTrait
{

    private KernelBrowser $client;

    protected string $uriPrefix = '/rest-api';

    protected function initializeApiClient(): void
    {
        if (!isset($this->client)) {
            $this->client = static::createClient();
        }
    }

    protected function getApiClient(): KernelBrowser
    {
        return $this->client;
    }

    protected function extractData(Response $response)
    {
        return RestApiTestHelper::extractData($response);
    }

    protected function sendRequest(?string $uri = null, string $method = 'GET', array $data = [], array $headers = []): Response
    {
        list($uri, $method, $data, $headers) = $this->prepareApiRequest($uri, $method, $data, $headers);
        $server = RestApiTestHelper::headersToServerParameters($headers);
        if ($method == 'GET' && !empty($data)) {
            $requestQuery = http_build_query($data, "", '&');
            $uri .= '?' . $requestQuery;
        }
        $this->getApiClient()->jsonRequest($method, $this->uriPrefix . $uri, $data, $server);
        return $this->getApiClient()->getResponse();
    }

    protected function prepareApiRequest(?string $uri = null, string $method = 'GET', array $data = [], array $headers = []): array
    {
        $methods = get_class_methods($this);
        foreach ($methods as $callMethod) {
            if (str_starts_with($callMethod, 'prepareApiRequestWith')) {
                list($uri, $method, $data, $headers) = call_user_func_array([$this, $callMethod], [$uri, $method, $data, $headers]);
            }
        }
        return [$uri, $method, $data, $headers];
    }
}
