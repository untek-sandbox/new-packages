<?php

namespace Untek\Component\Byte\Helpers;

use Untek\Component\Measure\Byte\Enums\ByteEnum;

class ByteSizeFormatHelper
{

    public static function sizeUnit(int $sizeByte)
    {
        $units = ByteEnum::allUnits();
        foreach ($units as $name => $value) {
            if ($sizeByte / $value < ByteEnum::STEP) {
                return $value;
            }
        }
    }

    public static function sizeFormat(int $sizeByte, $precision = 2)
    {
        $unitKey = self::sizeUnit($sizeByte);
        $value = round($sizeByte / $unitKey, 2);
        $labels = ByteEnum::getLabels();
        $label = $labels[$unitKey];
        return $value . ' ' . $label;
    }
}
