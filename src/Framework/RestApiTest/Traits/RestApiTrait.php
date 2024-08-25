<?php

namespace Untek\Framework\RestApiTest\Traits;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait RestApiTrait
{

    private KernelBrowser $client;

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

//    private static ?KernelBrowser $client;

    /*protected function initializeApiClient(): void
    {

    }

    protected function getApiClient(): KernelBrowser
    {
        if (!isset(static::$client)) {
            static::$client = static::createClient();
        }
        return static::$client;
    }*/
}
