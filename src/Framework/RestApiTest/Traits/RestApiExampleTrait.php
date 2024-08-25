<?php

namespace Untek\Framework\RestApiTest\Traits;

trait RestApiExampleTrait
{

    private bool $asExample = false;

    protected function prepareApiRequestWithExample(?string $uri = null, string $method = 'GET', array $data = [], array $headers = []): array
    {
        if ($this->asExample) {
            $headers['AsExample'] = 1;
            $this->asExample = false;
        }
        return [$uri, $method, $data, $headers];
    }

    protected function markRequestAsExample(): void
    {
        $this->asExample = true;
    }
}
