<?php

namespace Untek\Develop\Package\Domain\Libs\Deps;

use Untek\Component\PhpParser\Helpers\PhpTokenHelper;
use Untek\Component\Text\Helpers\TextHelper;
use Untek\Core\Instance\Helpers\ClassHelper;

class PhpClassNameInQuotedStringParser
{

    public function parse(string $code)
    {
        $classes = [];
        $tokenCollection = PhpTokenHelper::getTokens($code);
        foreach ($tokenCollection as $tokenEntity) {
            if ($tokenEntity->getName() == 'T_CONSTANT_ENCAPSED_STRING') {
                $className = $tokenEntity->getData();
                $className = trim($className, '\'"');
                $className = TextHelper::removeDoubleChar($className, '\\');
                if (ClassHelper::isExist($className)) {
                    $classes[] = $className;
                }

            }
        }
        return $classes;
    }
}
