<?php

namespace Untek\Component\FormatAdapter\Helpers;

use Untek\Component\Arr\Helpers\ArrayPathHelper;
use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Component\FileSystem\Helpers\FileStorageHelper;
use Yiisoft\Arrays\ArrayHelper;

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
        $data['code'] = ArrayPathHelper::getValue($data, 'code');
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
