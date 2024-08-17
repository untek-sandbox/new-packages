<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Core\Instance\Fork\Resolution\ConstraintResolver;
use Untek\Core\Instance\Libs\InstanceProvider;
use Untek\Core\Instance\Libs\Resolvers\ArgumentDescriptor;
use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;
use Untek\Core\Instance\Libs\Resolvers\InstanceResolver;
use Untek\Core\Kernel\Config\CallableConfigLoader;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire();

//    $services
//        ->load('Untek\Core\Instance\\', __DIR__ . '/../../..')
//        ->exclude([
//            __DIR__ . '/../../../{resources,Domain,Application/Commands,Application/Queries,Application/Validators}',
//            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Schema.php,Normalizer.php}',
//            __DIR__ . '/../../../**/{Dto,Enums}',
//        ]);

    $services->set(InstanceResolver::class);
//    $services->set(InstanceProvider::class);
//    $services->set(ArgumentDescriptor::class);
//    $services->set(ConstraintResolver::class);
//    $services->set(ArgumentMetadataResolver::class);
//    $services->set(CallableConfigLoader::class);
};