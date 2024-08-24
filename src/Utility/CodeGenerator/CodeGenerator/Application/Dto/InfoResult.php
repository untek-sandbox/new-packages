<?php

namespace Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\ResultInterface;

class InfoResult implements ResultInterface
{

    private ?string $name = null;
    private string $content;

    public function __construct(
        string $name,
        string $content,
    )
    {
        if (empty($name)) {
            throw new \RuntimeException('Empty name in ' . self::class);
        }
        if (empty($content)) {
            throw new \RuntimeException('Empty content in ' . self::class);
        }
        $this->name = $name;
        $this->content = $content;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}