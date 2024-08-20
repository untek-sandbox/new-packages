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
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Persistence\Contract\Interfaces\RepositoryFindOneByIdInterface;
use Untek\Persistence\Contract\Interfaces\RepositoryUpdateInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Symfony\Contracts\Translation\TranslatorInterface;

class <?= $className ?>

{

    public function __construct(
        private TranslatorInterface $translator,
        private RepositoryFindOneByIdInterface|RepositoryUpdateInterface $repository,
    )
    {
    }

    /**
     * @param \<?= $commandClassName ?> $command
     * @throws UnprocessableEntityException
     * @throws NotFoundException
     */
    public function __invoke(\<?= $commandClassName ?> $command): void
    {
        $validator = new \<?= $validatorClassName ?>($this->translator);
        $validator->validate($command);

        $entity = $this->repository->findOneById($command->getId());
        PropertyHelper::mergeObjects($command, $entity);
        $this->repository->update($entity);
    }
}