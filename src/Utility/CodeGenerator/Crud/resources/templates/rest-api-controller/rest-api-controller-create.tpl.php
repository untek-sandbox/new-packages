<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $commandFullClassName
 * @var string $routeName
 */

?>

namespace <?= $namespace ?>;

use Symfony\Component\Routing\Annotation\Route;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Core\App\Services\ControllerAccessChecker;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers\AbstractCreateRestApiController;
use <?= $commandFullClassName ?>;
use <?= $schemaClassName ?>;

#[Route('/<?= $uri ?>', methods: ['<?= $method ?>'], name: '<?= $routeName ?>')]
class <?= $className ?> extends AbstractCreateRestApiController
{

    protected string $routeName = '<?= $routeName ?>';

    public function __construct(
        private CommandBusInterface $bus,
        protected UrlGeneratorInterface $urlGenerator,
        private ControllerAccessChecker $accessChecker,
        protected <?= \Untek\Core\Instance\Helpers\ClassHelper::getClassOfClassName($schemaClassName) ?> $schema,
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->accessChecker->denyAccessUnlessAuthenticated();
        /** @var <?= $commandClassName ?> $command */
        $command = $this->createForm($request, <?= $commandClassName ?>::class);
        $entity = $this->bus->handle($command);
        return $this->createResponse($entity);
    }
}