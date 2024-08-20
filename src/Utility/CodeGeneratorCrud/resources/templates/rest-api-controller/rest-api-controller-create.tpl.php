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

use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Component\App\Services\ControllerAccessChecker;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers\AbstractCreateRestApiController;
use <?= $commandFullClassName ?>;
use <?= $schemaClassName ?>;

class <?= $className ?> extends AbstractCreateRestApiController
{

    protected string $routeName = '<?= $routeName ?>';

    public function __construct(
        private CommandBusInterface $bus,
        UrlGeneratorInterface $urlGenerator,
        private ControllerAccessChecker $accessChecker,
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->schema = new <?= \Untek\Core\Instance\Helpers\ClassHelper::getClassOfClassName($schemaClassName) ?>();
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