<?php

namespace Untek\Core\Contract\User\Exceptions;

//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Exception;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Untek\Component\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

/**
 * Не аутентифицированный пользователь
 */
class UnauthorizedException extends AuthenticationException
{

}
