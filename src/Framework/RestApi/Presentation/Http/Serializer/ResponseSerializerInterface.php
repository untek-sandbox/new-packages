<?php

namespace Untek\Framework\RestApi\Presentation\Http\Serializer;

use Symfony\Component\HttpFoundation\Response;
use Untek\Component\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

interface ResponseSerializerInterface
{

    public function encode($data): Response;
}
