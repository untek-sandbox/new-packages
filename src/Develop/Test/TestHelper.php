<?php

namespace Untek\Develop\Test;

use Untek\Component\FormatAdapter\Store;

class TestHelper
{

    public static function printData(mixed $data, ?string $format = 'php')
    {
        if ($format == 'php') {
            $data = (new Store('php'))->encode($data);
        } elseif ($format == 'json') {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } elseif ($format == null) {
            $data = print_r($data, true);
        }
        echo $data;
        echo PHP_EOL;
        exit();
    }
}
