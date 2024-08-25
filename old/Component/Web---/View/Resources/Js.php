<?php

namespace Untek\Component\Web\View\Resources;

use Untek\Component\Arr\Helpers\ExtArrayHelper;

class Js extends BaseResource
{

    public function registerVar(string $name, $value)
    {
        if (is_object($value)) {
            $value = ExtArrayHelper::toArray($value);
        }
        $json = json_encode($value);
        $code = "$name = " . $json . ";";
        $this->registerCode($code);
    }
}
