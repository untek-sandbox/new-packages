<?php

namespace Untek\Lib\QrBox\Libs;

use Untek\Component\Encoder\Encoders\ChainEncoder;
use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Core\Collection\Libs\Collection;
use Yiisoft\Arrays\ArrayHelper;

class ClassEncoder
{

    private $assoc = [];

    public function __construct(array $assoc)
    {
        $this->assoc = $assoc;
    }

    private function encoderToClass(string $name)
    {
        return ExtArrayHelper::getValue($this->assoc, $name);
    }

    public function encodersToClasses(array $names): ChainEncoder
    {
        $classes = [];
        foreach ($names as $name) {
            $classes[] = $this->encoderToClass($name);
        }
        $encoders = new ChainEncoder(new Collection($classes));
        return $encoders;
    }
}