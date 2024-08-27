<?php

namespace Untek\Component\FormatAdapter\Drivers;

class Csv implements DriverInterface
{

    public function decode($content)
    {
        $content = trim($content);
        $lines = explode("\n", $content);
        $data = array_map('str_getcsv', $lines);
        return $data;
    }

    public function encode($data)
    {
        $content = '';
        foreach ($data as $columns) {
            $line = implode(',', $columns);
            $content .= $line . PHP_EOL;
        }
        $content = trim($content);
        return $content;
    }

}
