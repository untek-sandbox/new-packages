<?php

namespace Untek\Framework\RestApi\Infrastructure;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Component\Code\Helpers\DeprecateHelper;
use Untek\Core\Kernel\Bundle\BaseBundle;

DeprecateHelper::hardThrow();

class RestApiErrorHandleBundle extends BaseBundle
{
    public function getName(): string
    {
        return 'rest-api-error-handle';
    }

    public function build(ContainerBuilder $containerBuilder)
    {
        $this->importServices($containerBuilder, __DIR__ . '/../Resources/config/services/error-handle.php');
    }

    public function boot(ContainerInterface $container): void
    {
        $this->configureFromPhpFile(__DIR__ . '/../Resources/config/error-handle-event-dispatcher.php', $container);
    }
}
