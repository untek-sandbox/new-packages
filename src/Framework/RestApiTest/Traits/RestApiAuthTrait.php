<?php

namespace Untek\Framework\RestApiTest\Traits;

use Symfony\Component\HttpFoundation\Response;
use Tests\Shared\UserToken;
use Untek\Framework\RestApiTest\Helpers\RestApiTestHelper;

trait RestApiAuthTrait
{

    private ?string $authByToken = null;

    protected function sendRequest(?string $uri = null, string $method = 'GET', array $data = [], array $headers = []): Response
    {
        list($uri, $method, $data, $headers) = $this->prepareApiRequest($uri, $method, $data, $headers);
        $server = RestApiTestHelper::headersToServerParameters($headers);
        if ($method == 'GET' && !empty($data)) {
            $requestQuery = http_build_query($data, "", '&');
            $uri .= '?' . $requestQuery;
        }
        $this->getApiClient()->jsonRequest($method, '/rest-api' . $uri, $data, $server);
        return $this->getApiClient()->getResponse();
    }

    protected function printResponseData(Response $response, ?string $path = null, ?string $format = 'php')
    {
        RestApiTestHelper::printResponseData($response, $path, $format);
    }

    protected function extractHeaders(Response $response)
    {
        return RestApiTestHelper::extractHeaders($response);
    }

    protected function prepareApiRequestWithAuth(?string $uri = null, string $method = 'GET', array $data = [], array $headers = []): array
    {
        if ($this->authByToken) {
            $headers['Authorization'] = $this->authByToken;
        }
        return [$uri, $method, $data, $headers];
    }

    /**$entity
     * @param string $login
     * @throws Exception
     */
    protected function authByLogin(string $login): void
    {
        $this->authByToken = UserToken::findOneByLogin($login);
    }


    protected function logout(): void
    {
        $this->authByToken = null;
    }


}
