<?php

namespace Untek\Develop\Package\Domain\Libs\Deps;

use Untek\Component\Code\Helpers\PhpTokenHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\TextHelper;

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
