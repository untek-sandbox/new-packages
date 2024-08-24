<?php

namespace Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\ResultInterface;

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

    public function addInfo(InfoResult $result): self
    {
        $name = $result->getName();
        if ($this->has($name)) {
            $existsResult = $this->get($name);
            if ($existsResult instanceof ResultList) {
                $existsResult->addItem($result);
            } else {
                $this->items[$name] = new ResultList($name, [$existsResult, $result]);
            }
        } else {
            $this->items[$name] = $result;
        }

        return $this;
    }

    public function addFile(FileResult $result): self
    {
        $name = $result->getName();
        if ($this->has($name)) {
            $this->get($name)->setContent($result->getContent());
        }
        $this->items[$name] = $result;
        return $this;
    }

    private function add(ResultInterface $result): self
    {
        $name = $result->getName();
        $this->items[$name] = $result;
        return $this;
    }

    public function get(string $name): ResultInterface
    {
        return $this->items[$name];
    }

    public function has(string $name): bool
    {
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