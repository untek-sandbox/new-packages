<?php

namespace Untek\Utility\CodeGenerator\Database\Application\Handlers;

use Untek\Component\Cqrs\Application\Abstract\CqrsHandlerInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;
use Untek\Utility\CodeGenerator\Database\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGenerator\Database\Application\Validators\GenerateDatabaseCommandValidator;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Generators\ContainerConfigGenerator;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Generators\EloquentRepositoryGenerator;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Generators\FixtureGenerator;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Generators\MigrationGenerator;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Generators\ModelGenerator;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Generators\NormalizerGenerator;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Generators\RelationGenerator;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Generators\RepositoryGenerator;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Generators\RepositoryInterfaceGenerator;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Generators\SeedGenerator;

class GenerateDatabaseCommandHandler implements CqrsHandlerInterface
{

    public function __construct(
        protected GenerateResultCollection $collection,
        private GenerateDatabaseCommandValidator $commandValidator,
    )
    {
    }

    /**
     * @param GenerateDatabaseCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateDatabaseCommand $command)
    {
//        $validator = new GenerateDatabaseCommandValidator();
        $this->commandValidator->validate($command);

        $generators = [
            new RepositoryInterfaceGenerator($this->collection),
            new NormalizerGenerator($this->collection),
            new RelationGenerator($this->collection),
            new EloquentRepositoryGenerator($this->collection),
            new ModelGenerator($this->collection),
            new SeedGenerator($this->collection),
            new FixtureGenerator($this->collection),
            new ContainerConfigGenerator($this->collection),
            new MigrationGenerator($this->collection),
        ];

        GeneratorHelper::generate($generators, $command);
//        GeneratorHelper::dump($this->collection);
    }
}