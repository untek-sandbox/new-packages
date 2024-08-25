<?php

namespace Untek\Utility\CodeGenerator\Crud\Infrastructure\Factories;

use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Utility\CodeGenerator\Application\Application\Enums\TypeEnum;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Factories\GenerateApplicationCommandFactory;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Generators\CommandGenerator;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Generators\CommandHandlerGenerator;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Generators\CommandValidatorGenerator;
use Untek\Utility\CodeGenerator\Crud\Infrastructure\Enums\CrudOperationEnum;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Factories\GenerateRestApiCommandFactory;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators\ControllerGenerator;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators\RestApiSchemeGenerator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yiisoft\Arrays\ArrayHelper;

class GenerateCrudCommandsFactory
{

    public static function create(array $crud, string $namespace, string $modelName = null, array $dbProperties): array
    {
//        $crud = self::prepareDefinition($crud);
        $commands = [];
        foreach ($crud as $item) {
            $type = $item['type'];
            $name = $item['name'];
            $uri = $item['uri'];
            $method = $item['method'];
            $properties = $item['properties'] ?? [];
            $parameters = $item['parameters'] ?? [];

            $commands[] = GenerateRestApiCommandFactory::create($namespace, $type, $name, $uri, $method, null, $parameters, $modelName, $dbProperties);
            $commands[] = GenerateApplicationCommandFactory::create($namespace, $type, $name, $properties, $parameters, $modelName);
        }
        return $commands;
    }

    public static function prepareDefinition(
        array $crud,
        string $namespace,
        string $modelName = null,
        string $uriPrefix,
    ): array
    {
        $repositoryInterface = $namespace . '\\Application\\Services\\' . $modelName . 'RepositoryInterface';
        $crudTemplate = [
            CrudOperationEnum::LIST => [
                'type' => TypeEnum::QUERY,
                'name' => "Get{$modelName}List",
                'uri' => $uriPrefix,
                'method' => 'GET',
                'parameters' => [
                    CommandHandlerGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/handler/get-list-handler.tpl.php',
                        'constructArguments' => [
                            $repositoryInterface,
                        ],
                    ],
                    CommandValidatorGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/validator/get-list-query-validator.tpl.php',
                    ],
                    CommandGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/command/get-list-query.tpl.php',
                    ],
                    ControllerGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/rest-api-controller/rest-api-controller-list.tpl.php',
                    ],
                ],
                'properties' => [],
            ],
            CrudOperationEnum::CREATE => [
                'type' => TypeEnum::COMMAND,
                'name' => "Create{$modelName}",
                'uri' => $uriPrefix,
                'method' => 'POST',
                'parameters' => [
                    CommandHandlerGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/handler/create-handler.tpl.php',
                        'constructArguments' => [
                            $repositoryInterface,
                        ],
                    ],
                    ControllerGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/rest-api-controller/rest-api-controller-create.tpl.php',
                    ],
                ],
            ],
            CrudOperationEnum::ONE => [
                'type' => TypeEnum::QUERY,
                'name' => "Get{$modelName}ById",
                'uri' => $uriPrefix . '/{id}',
                'method' => 'GET',
                'properties' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                    ],
                    [
                        'name' => 'expand',
                        'type' => 'array',
                        'defaultValue' => [],
//                'required' => false,
                    ],
                ],
                'parameters' => [
                    CommandHandlerGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/handler/get-one-handler.tpl.php',
                        'constructArguments' => [
                            $repositoryInterface,
                        ],
                    ],
                    CommandValidatorGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/validator/get-one-query-validator.tpl.php',
                    ],
                    CommandGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/command/get-by-id-query.tpl.php',
                    ],
                    ControllerGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/rest-api-controller/rest-api-controller-one.tpl.php',
                    ],
                ],
            ],
            CrudOperationEnum::UPDATE => [
                'type' => TypeEnum::COMMAND,
                'name' => "Update{$modelName}ById",
                'uri' => $uriPrefix . '/{id}',
                'method' => 'PUT',
                'parameters' => [
                    CommandHandlerGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/handler/update-handler.tpl.php',
                        'constructArguments' => [
                            $repositoryInterface,
                        ],
                    ],
                    ControllerGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/rest-api-controller/rest-api-controller-update.tpl.php',
                    ],
                    RestApiSchemeGenerator::class => [
                        'skip' => true,
                    ],
                ],
                'properties' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                    ],
                    /*[
                        'name' => 'parent_id',
                        'type' => 'int',
                    ],
                    [
                        'name' => 'title',
                        'type' => 'array',
                    ],*/
                ],
            ],
            CrudOperationEnum::DELETE => [
                'type' => TypeEnum::COMMAND,
                'name' => "Delete{$modelName}ById",
                'uri' => $uriPrefix . '/{id}',
                'method' => 'DELETE',
                'parameters' => [
                    CommandHandlerGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/handler/delete-handler.tpl.php',
                        'constructArguments' => [
                            $repositoryInterface,
                        ],
                    ],
                    CommandValidatorGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/validator/delete-command-validator.tpl.php',
                    ],
                    ControllerGenerator::class => [
                        'template' => __DIR__ . '/../../../Crud/resources/templates/rest-api-controller/rest-api-controller-delete.tpl.php',
                    ],
                    RestApiSchemeGenerator::class => [
                        'skip' => true,
                    ],
                ],
                'properties' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                    ],
                ],
            ],
        ];
        return ArrayHelper::merge($crudTemplate, $crud);
    }
}