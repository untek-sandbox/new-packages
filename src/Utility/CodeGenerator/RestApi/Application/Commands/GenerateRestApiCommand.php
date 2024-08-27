<?php

namespace Untek\Utility\CodeGenerator\RestApi\Application\Commands;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Commands\AbstractCommandCommand;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\CommandInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Traits\CommandNamespaceTrait;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Traits\CommandParameterTrait;

class GenerateRestApiCommand extends AbstractCommandCommand implements CommandInterface
{

    use CommandParameterTrait;
    use CommandNamespaceTrait;

    private string $namespace;
    private string $moduleName;
    private string $commandClass;
    private string $uri;
    private string $httpMethod;
    private string $version;
    private array $templates = [];
    private ?string $modelName;
    private array $properties = [];

    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    public function setModuleName(string $moduleName): void
    {
        $this->moduleName = $moduleName;
    }

    /*public function getCommandClass(): string
    {
        if(!empty($this->commandClass)) {
            return $this->commandClass;
        }
        return ApplicationPathHelper::getCommandClass($this);
    }*/

    public function setCommandClass(string $commandClass): void
    {
        $this->commandClass = $commandClass;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function setHttpMethod(string $httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getModelName(): ?string
    {
        return $this->modelName;
    }

    public function setModelName(?string $modelName): void
    {
        $this->modelName = $modelName;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }
}