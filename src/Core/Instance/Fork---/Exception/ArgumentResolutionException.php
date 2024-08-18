<?php

namespace Untek\Core\Instance\Fork\Exception;

use Untek\Core\Instance\Fork\Argument\ArgumentDescription;

class ArgumentResolutionException extends ResolutionException
{
    /**
     * @var ArgumentDescription
     */
    private $argumentDescription;

    public function __construct($message, ArgumentDescription $argumentDescription, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->argumentDescription = $argumentDescription;
    }

    /**
     * @return ArgumentDescription
     */
    public function getArgumentDescription()
    {
        return $this->argumentDescription;
    }
}
