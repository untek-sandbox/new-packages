<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $commandFullClassName
 */

?>

namespace <?= $namespace ?>;

use Symfony\Component\Routing\Annotation\Route;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers\AbstractRestApiController;
use Untek\Core\App\Services\ControllerAccessChecker;
use <?= $commandFullClassName ?>;

#[Route('/<?= $uri ?>', methods: ['<?= $method ?>'], name: '<?= $routeName ?>')]
class <?= $className ?> extends AbstractRestApiController
{

    public function __construct(
        private CommandBusInterface $bus,
        protected UrlGeneratorInterface $urlGenerator,
        private ControllerAccessChecker $accessChecker,
    )
    {
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $this->accessChecker->denyAccessUnlessAuthenticated();
        $command = new <?= $commandClassName ?>();
        $command->setId($id);
        $this->accessChecker->denyAccessUnlessGranted('ROLE_USER', $command);
        $this->bus->handle($command);
        return $this->emptyResponse();
    }
}