<?php

namespace Untek\Component\FormatAdapter\Helpers;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\FileSystem\Helpers\FileStorageHelper;

class FileGeneratorHelper
{

    public static function generate($data)
    {
        $code = self::generateCode($data);
        $fileName = $data['fileName'];
        FileStorageHelper::save($fileName, $code);
    }

    private static function generateCode($data)
    {
        $data['code'] = ArrayHelper::getValue($data, 'code');
        $data['code'] = trim($data['code'], PHP_EOL);
        $data['code'] = PHP_EOL . $data['code'];
        $code = self::getClassCodeTemplate();
        $code = str_replace('{code}', $data['code'], $code);
        return $code;
    }

    private static function getClassCodeTemplate()
    {
        $code = <<<'CODE'
<?php
{code}
CODE;
        return $code;
    }

}
