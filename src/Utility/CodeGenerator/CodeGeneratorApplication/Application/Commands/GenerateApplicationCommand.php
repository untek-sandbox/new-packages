<?php

namespace Untek\Utility\CodeGenerator\CodeGeneratorApplication\Application\Commands;

use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Commands\AbstractCommand;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Commands\AbstractCommandCommand;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Traits\CommandNamespaceTrait;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Traits\CommandParameterTrait;

class GenerateApplicationCommand extends AbstractCommandCommand
{

    use CommandParameterTrait;
    use CommandNamespaceTrait;

    private ?string $modelName = null;

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    public function getModelName(): ?string
    {
        return $this->modelName;
    }

    public function setModelName(?string $modelName): void
    {
        $this->modelName = $modelName;
    }
}