<?php

namespace Untek\Component\Web\View\Resources;

use Yiisoft\Arrays\ArrayHelper;

class Js extends BaseResource
{

    public function registerVar(string $name, $value)
    {
        if (is_object($value)) {
            $value = ArrayHelper::toArray($value);
        }
        $json = json_encode($value);
        $code = "$name = " . $json . ";";
        $this->registerCode($code);
    }
}
