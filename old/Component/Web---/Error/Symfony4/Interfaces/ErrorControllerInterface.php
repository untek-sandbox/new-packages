<?php

namespace Untek\Component\Web\Error\Symfony4\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Untek\Component\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

interface ErrorControllerInterface
{

    public function handleError(Request $request, \Exception $exception): Response;
}
