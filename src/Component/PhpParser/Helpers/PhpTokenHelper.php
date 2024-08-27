<?php

namespace Untek\Component\PhpParser\Helpers;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Untek\Component\PhpParser\Model\PhpToken;

class PhpTokenHelper
{

    /**
     * @param string $code
     * @return Collection | PhpToken[]
     */
    public static function getTokens(string $code): Collection
    {
        $collection = new ArrayCollection();
        $tokens = token_get_all($code, TOKEN_PARSE);
        foreach ($tokens as &$token) {
            $tokenTypeId = is_array($token) ? $token[0] : 262;
            $tokenCode = is_array($token) ? $token[1] : $token;
            $tokenEntity = new PhpToken();
            $tokenEntity->setId($tokenTypeId);
            $tokenEntity->setName(token_name($tokenTypeId));
            $tokenEntity->setData($tokenCode);
            $collection->add($tokenEntity);
        }
        return $collection;
    }
}
