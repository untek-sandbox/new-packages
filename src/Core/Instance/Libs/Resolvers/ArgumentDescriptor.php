<?php

namespace Untek\Core\Instance\Libs\Resolvers;

use Untek\Core\Instance\Fork\Argument\ArgumentDescription;
use Untek\Core\Instance\Fork\Argument\ArgumentDescriptions;
use Doctrine\Common\Util\ClassUtils;

class ArgumentDescriptor extends \Untek\Core\Instance\Fork\Argument\ArgumentDescriptor
{
    /**
     * Get argument descriptions of a callable.
     *
     * @param $callable
     *
     * @return ArgumentDescriptions
     */
    public function getDescriptions($callable)
    {
        $reflection = $this->getReflection($callable);
        $descriptions = new ArgumentDescriptions();

        foreach ($reflection->getParameters() as $parameter) {
            $descriptions->add(new ArgumentDescription(
                $parameter->name,
                $parameter->getPosition(),
                $this->getParameterType($parameter),
                !$parameter->isOptional(),
                $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null
            ));
        }

        return $descriptions;
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getValueType($value)
    {
        if (is_object($value)) {
            return $this->getObjectClass($value);
        } elseif (is_array($value)) {
            return ArgumentDescription::TYPE_ARRAY;
        }

        return ArgumentDescription::TYPE_SCALAR;
    }

    /**
     * @param \ReflectionParameter $parameter
     *
     * @return string
     */
    private function getParameterType(\ReflectionParameter $parameter)
    {
        if (null !== ($class = $parameter->getType())) {
            return $class->__toString();
        } elseif ($parameter->isArray()) {
            return ArgumentDescription::TYPE_ARRAY;
        }

        return ArgumentDescription::TYPE_SCALAR;
    }

    /**
     * @param $callable
     *
     * @return \ReflectionFunctionAbstract
     */
    private function getReflection($callable)
    {
        if ($callable instanceof \ReflectionFunctionAbstract) {
            return $callable;
        }

        /*if(is_array($callable) && count($callable) > 1 && is_string($callable[0])) {
            $reflectionClass = new \ReflectionClass($callable[0]);
            return $reflectionClass->getMethod($callable[1]);
        } elseif (is_array($callable)) {
            $reflectionClass = new \ReflectionClass($callable[0]);
            return $reflectionClass->getMethod($callable[1]);
        } elseif(!is_callable($callable)) {
            throw new \RuntimeException('Got a non-callable');
        }*/

        if(is_array($callable) && count($callable) > 1 && is_string($callable[0])) {
            $reflectionClass = new \ReflectionClass($callable[0]);
            return $reflectionClass->getMethod($callable[1]);
        } elseif (!is_callable($callable)) {
            throw new \RuntimeException('Got a non-callable');
        } elseif (is_array($callable)) {
            $reflectionClass = new \ReflectionClass($callable[0]);

            return $reflectionClass->getMethod($callable[1]);
        }

        return new \ReflectionFunction($callable);
    }

    /**
     * Get object class.
     *
     * It uses the Doctrine's `ClassUtils::getClass` method if exists, else the native `get_class` function.
     *
     * @param object $object
     *
     * @return string
     */
    private function getObjectClass($object)
    {
        return class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($object) : get_class($object);
    }
}
