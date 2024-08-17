<?php

namespace Untek\Framework\RestApi\Presentation\Http\Symfony\Interfaces;

interface RestApiSchemaInterface
{

    public function encode(mixed $data): mixed;
}