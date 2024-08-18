<?php

namespace Untek\Develop\Package\Domain\Libs\Deps;

use Untek\Component\Code\Helpers\PhpHelper;
use Untek\Component\Code\Helpers\PhpTokenHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\TextHelper;

class PhpNameParser
{

    public function parse(string $code)
    {
        return $this->parseNames($code);
    }

    private function parseNames(string $code): array
    {
        $exp = '((?:\\\\)?[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*(\\\\[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)*)';
        preg_match_all(
            "/$exp/i",
            $code,
            $matches
        );
        $names = [];
        foreach ($matches[0] as $item) {
            $isValidName = preg_match('/[a-z0-9_\\\\]+/i', $item);
            $isAllow = ! PhpHelper::isReservedName($item);
            if($isValidName && $isAllow) {
                $names[] = $item;
            }
        }

        $names = array_unique($names);
        $names = array_values($names);
        sort($names);

        return $names;
    }
}
