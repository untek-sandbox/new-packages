<?php

namespace Untek\Component\FormatAdapter\Drivers;

use Symfony\Component\Yaml\Yaml as SymfonyYaml;

class Yaml implements DriverInterface
{

    private $indent;

    public function __construct(int $indent = 4)
    {
        $this->indent = $indent;
    }

    public function decode($content)
    {
        $data = SymfonyYaml::parse($content);
        return $data;
    }

    public function encode($data)
    {
        $content = SymfonyYaml::dump($data, 1000, $this->indent,SymfonyYaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
        return $content;
    }

}