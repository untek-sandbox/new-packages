<?php

namespace Untek\Database\Base\Hydrator;

interface DbNormalizerInterface
{

    public function denormalize(array $data, string $type): object;

    public function normalize(object $object): array;
}