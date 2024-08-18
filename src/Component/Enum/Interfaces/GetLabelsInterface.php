<?php

namespace Untek\Component\Enum\Interfaces;
use Untek\Component\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

/**
 * Возможность получения названий констант
 * 
 * Используется в Enum-классах
 */
interface GetLabelsInterface
{

    /**
     * Получить массив названий
     * @return array
     */
    public static function getLabels();
}
