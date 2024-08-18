<?php

namespace Untek\Utility\CodeGenerator\Application\Commands;

use Untek\Component\Code\Helpers\DeprecateHelper;
use Untek\Utility\CodeGenerator\Application\Traits\CommandNamespaceTrait;
use Untek\Utility\CodeGenerator\Application\Traits\CommandParameterTrait;

DeprecateHelper::hardThrow(
);

abstract class AbstractCommand
{

    use CommandNamespaceTrait;
//    use CommandParameterTrait;

}