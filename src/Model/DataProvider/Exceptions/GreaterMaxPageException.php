<?php

namespace Untek\Model\DataProvider\Exceptions;

use Exception;
use Untek\Component\Dev\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class GreaterMaxPageException extends Exception
{

}