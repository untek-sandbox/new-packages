<?php

namespace Untek\Framework\RestApiTest\Traits;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait RestApiLanguageTrait
{

    private ?string $language = null;

    protected function lang(string $language)
    {
        $this->language = $language;
    }

    protected function initializeApiLanguage(): void
    {
        if ($this->defaultLanguage()) {
            $this->lang($this->defaultLanguage());
        }
    }

    protected function prepareApiRequestWithLanguage(?string $uri = null, string $method = 'GET', array $data = [], array $headers = []): array
    {
        if ($this->language) {
            $headers['Language'] = $this->language;
        }
        return [$uri, $method, $data, $headers];
    }
}
