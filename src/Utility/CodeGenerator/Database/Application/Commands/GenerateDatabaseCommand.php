<?php

namespace Untek\Utility\CodeGenerator\Database\Application\Commands;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\CommandInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Traits\CommandNamespaceTrait;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Traits\CommandParameterTrait;
use Yiisoft\Strings\Inflector;

class GenerateDatabaseCommand implements CommandInterface
{

    use CommandParameterTrait;
    use CommandNamespaceTrait;

    private string $namespace;
    private string $tableName;
    private ?string $modelName = null;
    private array $properties;
    private string $repositoryDriver = 'eloquent';

    public function __construct(
        string $namespace = null,
        string $tableName = null,
        array $properties = null,
        string $modelName = null,
    )
    {
        if ($namespace) {
            $this->namespace = $namespace;
        }
        if ($tableName) {
            $this->tableName = $tableName;
        }
        if ($properties) {
            $this->properties = $properties;
        }
        if ($modelName) {
            $this->modelName = $modelName;
        }
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    public function getRepositoryDriver(): string
    {
        return $this->repositoryDriver;
    }

    public function setRepositoryDriver(string $repositoryDriver): void
    {
        $this->repositoryDriver = $repositoryDriver;
    }

    public function getModelName(): ?string
    {
        if ($this->modelName) {
            return $this->modelName;
        }
        return (new Inflector())->toPascalCase($this->getTableName());
    }

    public function setModelName(?string $modelName): void
    {
        $this->modelName = $modelName;
    }
}