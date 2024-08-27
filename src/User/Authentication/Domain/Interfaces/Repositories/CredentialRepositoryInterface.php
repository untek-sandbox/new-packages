<?php

namespace Untek\User\Authentication\Domain\Interfaces\Repositories;

use Doctrine\Common\Collections\Collection;
use Untek\Model\Repository\Interfaces\CrudRepositoryInterface;
use Untek\Persistence\Contract\Exceptions\NotFoundException;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Enums\CredentialTypeEnum;

interface CredentialRepositoryInterface //extends CrudRepositoryInterface
{

    /**
     * @param int $identityId
     * @param array|null $types
     * @return Collection | CredentialEntity[]
     */
    public function allByIdentityId(int $identityId, array $types = null): Collection;

    /**
     * @param string $credential
     * @param string $type
     * @return CredentialEntity
     * @throws NotFoundException
     */
    public function findOneByCredential(string $credential, string|array $type = CredentialTypeEnum::LOGIN): CredentialEntity;

    public function findOneByCredentialValue(string $credential): CredentialEntity;

    /**
     * @param string $validation
     * @param string $type
     * @return CredentialEntity
     * @throws NotFoundException
     */
    public function findOneByValidation(string $validation, string $type): CredentialEntity;
}

