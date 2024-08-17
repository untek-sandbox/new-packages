<?php

namespace Untek\Utility\CodeGenerator\Application\Dto;

use Untek\Core\FileSystem\Helpers\FileHelper;
use Untek\Utility\CodeGenerator\Application\Interfaces\ResultInterface;

class GenerateResultCollection
{

    /** @var array|ResultInterface[] */
    private array $items = [];

    public function __construct(array $items = [])
    {
        $this->fill($items);
    }

    public function merge(GenerateResultCollection $collection): self
    {
        $this->fill($collection->getAll());
        return $this;
    }

    protected function fill(array $items): self
    {
        if (empty($items)) {
            return $this;
        }
        foreach ($items as $item) {
            $this->add($item);
        }
        return $this;
    }

    public function add(ResultInterface $result): self
    {
        $name = FileHelper::normalizePath($result->getName());
        if($this->has($name)) {
            $this->get($name)->setContent($result->getContent());
        }
        $this->items[$name] = $result;
        return $this;
    }

    public function get(string $name): ResultInterface
    {
        $name = FileHelper::normalizePath($name);
        return $this->items[$name];
    }

    public function has(string $name): bool
    {
        $name = FileHelper::normalizePath($name);
        return isset($this->items[$name]);
    }

    /**
     * @return array|ResultInterface[]
     */
    public function getAll(): array
    {
        return $this->items;
    }
}