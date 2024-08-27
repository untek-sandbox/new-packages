<?php

namespace Untek\Lib\QrBox\Libs;

use Doctrine\Common\Collections\ArrayCollection;
use Untek\Component\Arr\Helpers\ArrayPathHelper;
use Untek\Component\Encoder\Encoders\ChainEncoder;

class ClassEncoder
{

    private $assoc = [];

    public function __construct(array $assoc)
    {
        $this->assoc = $assoc;
    }

    private function encoderToClass(string $name)
    {
        return ArrayPathHelper::getValue($this->assoc, $name);
    }

    public function encodersToClasses(array $names): ChainEncoder
    {
        $classes = [];
        foreach ($names as $name) {
            $classes[] = $this->encoderToClass($name);
        }
        $encoders = new ChainEncoder(new ArrayCollection($classes));
        return $encoders;
    }
}