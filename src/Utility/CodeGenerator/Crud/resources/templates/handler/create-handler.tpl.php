<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $validatorClassName
 * @var string $modelClass
 */

?>

namespace <?= $namespace ?>;

use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Component\Cqrs\Application\Abstract\CqrsHandlerInterface;
use <?= $repositoryInterfaceClassName ?>;

class <?= $className ?> implements CqrsHandlerInterface

{

    public function __construct(
        private TranslatorInterface $translator,
        private <?= \Untek\Core\Instance\Helpers\ClassHelper::getClassOfClassName($repositoryInterfaceClassName) ?> $repository,
    )
    {
    }

    /**
     * @param \<?= $commandClassName ?> $command
     * @return object
     * @throws UnprocessableEntityException
     */
    public function __invoke(\<?= $commandClassName ?> $command): object
    {
        $validator = new \<?= $validatorClassName ?>($this->translator);
        $validator->validate($command);

        $entity = new \<?= $modelClass ?>();
        PropertyHelper::mergeObjects($command, $entity);
        $this->repository->create($entity);
        return $entity;
    }
}