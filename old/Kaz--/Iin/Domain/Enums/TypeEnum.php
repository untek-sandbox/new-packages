<?php

namespace Untek\Kaz\Iin\Domain\Enums;

class TypeEnum
{

    const INDIVIDUAL = 'individual';
    const JURIDICAL = 'juridical';

    public static function getLabels()
    {
        return [
            self::INDIVIDUAL => 'Физическое лицо',
            self::JURIDICAL => 'Юридическое лицо',
        ];
    }
}
