<?php

namespace Untek\Utility\CodeGenerator\RestApi\Application\Handlers;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Untek\Component\Cqrs\Application\Abstract\CqrsHandlerInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\InfoResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Generators\ContainerConfigImportGenerator;
use Untek\Utility\CodeGenerator\RestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\RestApi\Application\Validators\GenerateRestApiCommandValidator;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators\ContainerConfigGenerator;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators\ControllerGenerator;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators\ControllerTestGenerator;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators\RestApiSchemeGenerator;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators\RoutConfigGenerator;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators\RoutConfigImportGenerator;

#[AsMessageHandler]
class GenerateRestApiCommandHandler implements CqrsHandlerInterface
{

    public function __construct(
        protected GenerateResultCollection $collection,
        private GenerateRestApiCommandValidator $commandValidator,
    )
    {
    }

    /**
     * @param GenerateRestApiCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateRestApiCommand $command)
    {
//        $validator = new GenerateRestApiCommandValidator();
        $this->commandValidator->validate($command);

        $generators = [
            new ControllerGenerator($this->collection),
            new RestApiSchemeGenerator($this->collection),
            new ControllerTestGenerator($this->collection),
//            new ContainerConfigGenerator($this->collection),
//            new ContainerConfigImportGenerator($this->collection, '/resources/config/services/rest-api.php', __DIR__ . '/../../../../../../../../config/rest-api/container.php'),
//            new RoutConfigGenerator($this->collection),
//            new RoutConfigImportGenerator($this->collection),
        ];

        GeneratorHelper::generate($generators, $command);
//        GeneratorHelper::dump($this->collection);

        $uri = '/rest-api/v' . $command->getVersion() . '/' . $command->getUri();
        $endpoint = $command->getHttpMethod() . ' ' . $uri;
        $this->collection->addInfo(new InfoResult('API endpoint', $endpoint));
    }
}