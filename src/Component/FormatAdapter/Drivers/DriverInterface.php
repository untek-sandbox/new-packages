<?php

namespace Untek\Component\FormatAdapter\Drivers;

interface DriverInterface
{

    public function decode($content);

    public function encode($data);

}