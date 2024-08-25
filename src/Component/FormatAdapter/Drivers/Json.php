<?php

namespace Untek\Component\FormatAdapter\Drivers;

use Untek\Component\Arr\Helpers\ExtArrayHelper;

class Json implements DriverInterface
{

    public function decode($content)
    {
        $data = json_decode($content);
        $data = ExtArrayHelper::toArray($data);
        return $data;
    }

    public function encode($data)
    {
        $content = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $content;
    }
}
