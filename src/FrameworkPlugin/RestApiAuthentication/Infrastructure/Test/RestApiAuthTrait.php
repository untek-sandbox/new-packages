<?php

namespace Untek\FrameworkPlugin\RestApiAuthentication\Infrastructure\Test;

use Symfony\Component\HttpFoundation\Response;
use Tests\Shared\UserToken;
use Untek\Framework\RestApiTest\Helpers\RestApiTestHelper;

trait RestApiAuthTrait
{

    private ?string $authByToken = null;

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
