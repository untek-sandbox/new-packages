<?php

namespace Untek\Utility\CodeGenerator\Application\Commands;

use Untek\Component\Enum\Helpers\EnumHelper;
use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;

abstract class AbstractCommandCommand //extends AbstractCommand
{

    protected string $commandName;
    protected string $commandType;

    public function getCamelizeName(): string
    {
        $camelizeName = (new Inflector())->toCamelCase($this->getCommandName());
        return $camelizeName . (new Inflector())->toCamelCase($this->getCommandType());
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    public function setCommandName(string $commandName): void
    {
        $this->commandName = $commandName;
    }

    public function getCommandType(): string
    {
        return $this->commandType;
    }

    public function setCommandType(string $commandType): void
    {
        EnumHelper::validate(TypeEnum::class, $commandType);
        $this->commandType = $commandType;
    }
}