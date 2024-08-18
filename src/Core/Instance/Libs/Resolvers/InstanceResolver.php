<?php

namespace Untek\Core\Instance\Libs\Resolvers;

use Psr\Container\ContainerInterface;
use Untek\Core\Contract\Common\Exceptions\InvalidConfigException;
use Untek\Core\Instance\Exceptions\ClassNotFoundException;
use Untek\Core\Instance\Helpers\ClassHelper;

class InstanceResolver
{

    public function __construct(protected ?ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Создать класс
     * @param $definition
     * @param array $constructParams
     * @return object
     * @throws \Untek\Core\Contract\Common\Exceptions\InvalidConfigException
     */
    public function create($definition, array $constructParams = []): object
    {
        if (empty($definition)) {
            throw new InvalidConfigException('Empty class config');
        }
        $definition = ClassHelper::normalizeComponentConfig($definition);

        if (empty($constructParams) && array_key_exists('__construct', $definition)) {
            $constructParams = $definition['__construct'];
            unset($definition['__construct']);
        }
        $handlerInstance = $this->createObject($definition['class'], $constructParams);

        ClassHelper::configure($handlerInstance, $definition);
        return $handlerInstance;
    }

    private function prepareParameters(string $className, string $methodName, array $constructionArgs): array
    {
        $methodParametersResolver = new MethodParametersResolver($this->container, $this);
        return $methodParametersResolver->resolve($className, $methodName, $constructionArgs);
    }

    /**
     * @param string $className
     * @param array $constructionArgs
     * @return object
     * @throws ClassNotFoundException
     */
    private function createObject(string $className, array $constructionArgs = []): object
    {
        if (!class_exists($className)) {
//            dd($className);
            throw new ClassNotFoundException($className);
        }
        $constructionArgs = $this->prepareParameters($className, '__construct', $constructionArgs);
        return $this->createObjectInstance($className, $constructionArgs);
    }

    public function createObjectInstance(string $className, array $constructionArgs): object
    {
        if (count($constructionArgs) && method_exists($className, '__construct')) {
//            $instance = new $className(...$constructionArgs);
            $reflectionClass = new \ReflectionClass($className);
            $instance = $reflectionClass->newInstanceArgs($constructionArgs);
        } else {
            $instance = new $className();
        }
        return $instance;
    }
}
