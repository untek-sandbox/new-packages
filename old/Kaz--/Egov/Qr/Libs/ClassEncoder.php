<?php

namespace Untek\Kaz\Egov\Qr\Libs;

use Doctrine\Common\Collections\ArrayCollection;
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
        return ArrayHelper::getValue($this->assoc, $name);
    }

    public function encodersToClasses(array $names): CollectionEncoder
    {
        $classes = [];
        foreach ($names as $name) {
            $classes[] = $this->encoderToClass($name);
        }
        $encoders = new CollectionEncoder(new ArrayCollection($classes));
        return $encoders;
    }

}