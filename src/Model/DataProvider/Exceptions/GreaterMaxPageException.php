<?php

namespace Untek\Model\DataProvider\Exceptions;

use Exception;
use Untek\Component\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class GreaterMaxPageException extends Exception
{

}