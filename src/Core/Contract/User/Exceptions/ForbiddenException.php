<?php

namespace Untek\Core\Contract\User\Exceptions;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

/**
 * Не достаточно полномочий
 */
class ForbiddenException extends AccessDeniedException
{

}
