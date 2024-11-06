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
use Untek\Component\Cqs\Application\Interfaces\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Helpers\QueryParameterHelper;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers\AbstractRestApiController;
use <?= $commandFullClassName ?>;
use <?= $schemaClassName ?>;

#[Route('/<?= $uri ?>', methods: ['<?= $method ?>'], name: '<?= $routeName ?>')]
class <?= $className ?> extends AbstractRestApiController
{

    public function __construct(
        private CommandBusInterface $bus,
        protected <?= \Untek\Core\Instance\Helpers\ClassHelper::getClassOfClassName($schemaClassName) ?> $schema,
    )
    {
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $query = new <?= $commandClassName ?>();
        $query->setId($id);
        QueryParameterHelper::fillQueryFromRequest($request, $query);

        $result = $this->bus->handle($query);
        $data = $this->encodeObject($result);
        return $this->serialize($data);
    }
}