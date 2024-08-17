<?php

namespace Untek\Core\Contract\Common\Exceptions;

use Exception;
use Untek\Core\Env\Helpers\EnvHelper;
use Untek\Core\Instance\Helpers\ClassHelper;

/**
 * Значение атрибута объекта только для чтения
 * 
 * Обычно это исключение вызывается при попытке записи значения атрибута, предназначенного только для чтения.
 */
class ReadOnlyAttributeException extends Exception
{

    private $class;
    private $attribute;

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class): void
    {
        $this->class = $class;
        $this->mess();
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function setAttribute($attribute): void
    {
        $this->attribute = $attribute;
        $this->mess();
    }

    private function mess() {
        $message = "Content is read only!";
        if($this->class && EnvHelper::isDev()) {
            $className = ClassHelper::getClassOfClassName($this->class);
            $message .= " See {$className}";
            if($this->attribute) {
                $message .= "::{$this->attribute}";
            }
        }
        $this->message = $message;
    }
}
