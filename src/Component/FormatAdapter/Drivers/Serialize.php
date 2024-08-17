<?php

namespace Untek\Component\FormatAdapter\Drivers;

class Serialize implements DriverInterface
{

    public function decode($content)
    {
        $data = unserialize($content);
        //$data = ArrayHelper::toArray($data);
        return $data;
    }

    public function encode($data)
    {
        $content = serialize($data);
        return $content;
    }

}