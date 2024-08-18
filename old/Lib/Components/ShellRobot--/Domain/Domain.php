<?php

namespace Untek\Lib\Components\ShellRobot\Domain;

use Untek\Component\Code\Helpers\DeprecateHelper;
use Untek\Domain\Domain\Interfaces\DomainInterface;

DeprecateHelper::hardThrow();

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'shellRobot';
    }
}
