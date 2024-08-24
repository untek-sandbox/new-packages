<?php

namespace Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\ResultInterface;

class ResultList implements ResultInterface
{

    private ?string $name = null;
    /** @var ResultInterface[] */
    private array $list = [];

    public function __construct(
        string $name,
        array $list = [],
    )
    {
        if (empty($name)) {
            throw new \RuntimeException('Empty name in ' . self::class);
        }
        $this->name = $name;
        $this->list = $list;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        $lines = [];
        foreach ($this->list as $item) {
            $lines[] = $item->getContent();
        }
        return implode(PHP_EOL, $lines);
    }

    public function getList(): string
    {
        return $this->list;
    }

    public function addItem(ResultInterface $result): self
    {
        $this->list[] = $result;
        return $this;
    }
}