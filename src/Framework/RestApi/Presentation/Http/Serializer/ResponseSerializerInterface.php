<?php

namespace Untek\Framework\RestApi\Presentation\Http\Serializer;

use Symfony\Component\HttpFoundation\Response;

interface ResponseSerializerInterface
{

    public function encode($data): Response;
}
