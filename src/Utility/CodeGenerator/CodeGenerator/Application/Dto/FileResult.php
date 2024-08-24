<?php

namespace Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\ResultInterface;

class FileResult implements ResultInterface
{

    private ?string $name = null;
    private string $content;
    private bool $isNew = false;

    public function __construct(
        string $name,
        string $content,
        ?bool $isNew = null,
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
        $this->isNew = $isNew === null ? !file_exists($name) : $isNew;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isNew(): bool
    {
        return $this->isNew;
    }
}