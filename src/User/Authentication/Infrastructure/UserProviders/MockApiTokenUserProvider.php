<?php

namespace Untek\User\Authentication\Infrastructure\UserProviders;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\Core\Contract\Common\Exceptions\InvalidMethodParameterException;
use Untek\Persistence\Contract\Exceptions\NotFoundException;
use Untek\User\Authentication\Domain\Helpers\TokenHelper;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;

class MockApiTokenUserProvider implements UserProviderInterface
{
    public function __construct(private TokenServiceInterface $tokenService, private IdentityRepositoryInterface $identityRepository)
    {
    }

    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    public function supportsClass(string $class): bool
    {
        return true;
    }

    public function loadUserByIdentifier(string $token): UserInterface
    {
        $user = $this->findUserByToken($token);
        return $user;
//        dd($user);
//        throw new UserNotFoundException();
    }

    private function findUserByToken(string $token): UserInterface
    {
        /*try {
            $tokenEntity = TokenHelper::parseToken($token);
        } catch (InvalidMethodParameterException $exception) {
            throw new UserNotFoundException();
        }*/



        try {
            if(is_numeric($token)) {
                $userId = (int)$token;
            } else {
                $userId = $this->tokenService->getIdentityIdByToken($token);
            }
            
            $user = $this->identityRepository->getUserById($userId);
            $user->eraseCredentials();
            return $user;
        } catch (NotFoundException) {
            throw new UserNotFoundException();
        }
    }
}
