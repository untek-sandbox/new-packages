<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $validatorClassName
 * @var string $modelName
 */

?>

namespace <?= $namespace ?>;

use Untek\Persistence\Contract\Exceptions\NotFoundException;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Symfony\Contracts\Translation\TranslatorInterface;
use <?= $repositoryInterfaceClassName ?>;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class <?= $className ?>

{

    public function __construct(
        private TranslatorInterface $translator,
        private <?= \Untek\Core\Instance\Helpers\ClassHelper::getClassOfClassName($repositoryInterfaceClassName) ?> $repository,
    )
    {
    }

    /**
     * @param \<?= $commandClassName ?> $query
     * @return object
     * @throws UnprocessableEntityException
     * @throws NotFoundException
     */
    public function __invoke(\<?= $commandClassName ?> $query): object
    {
        $validator = new \<?= $validatorClassName ?>($this->translator);
        $validator->validate($query);
        return $this->repository->findOneById($query->getId(), $query->getExpand());
    }
}