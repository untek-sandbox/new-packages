<?php

namespace Untek\Kaz\Iin\Domain\Enums\Rbac;

class IinPermissionEnum
{

    const GET_INFO = 'oIinGetInfo';

    public static function getLabels()
    {
        return [
            self::GET_INFO => 'Получить инфо об ИИН',
        ];
    }
}