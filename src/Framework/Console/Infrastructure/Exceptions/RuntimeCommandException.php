<?php

namespace Untek\Framework\Console\Infrastructure\Exceptions;

use Symfony\Component\Console\Exception\ExceptionInterface;
use Untek\Component\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

final class RuntimeCommandException extends \RuntimeException implements ExceptionInterface
{
}
