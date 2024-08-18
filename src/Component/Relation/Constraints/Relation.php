<?php

namespace Untek\Component\Relation\Constraints;

use Symfony\Component\Validator\Constraint;

\Untek\Component\Code\Helpers\DeprecateHelper::hardThrow();

class Relation extends Constraint
{

    public $foreignEntityClass;
    public $message = 'Item with ID "{{ value }}" not found';
}
