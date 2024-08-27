<?php

namespace Untek\Component\FileSystem\Helpers;

use PATHINFO_EXTENSION;
use Symfony\Component\Mime\MimeTypes;
use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Component\Dev\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class MimeTypeHelper
{

    /*public static function getMimeTypeByFileName(string $fileName): ?string
    {
        $mimeTypes = new MimeTypes();
        $mimeType = $mimeTypes->guessMimeType($fileName);
        return $mimeType;
//        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
//        return self::getMimeTypeByExt($ext);
    }

    public static function getMimeTypeByExt(string $ext): ?string
    {
        $mimeTypes = new MimeTypes();
        $types = $mimeTypes->getMimeTypes($ext);
        return ExtArrayHelper::first($types);
    }*/

    /*public static function getMimeTypesByExt(string $ext): ?array
    {
        return MimeTypes::getDefault()->getMimeTypes($ext);
    }

    public static function getExtensionByMime(string $mimeType): ?string
    {
        $extensions = self::getExtensionsByMime($mimeType);
        return ExtArrayHelper::first($extensions);
    }

    public static function getExtensionsByMime(string $mimeType): ?array
    {
        return MimeTypes::getDefault()->getExtensions($mimeType);
    }*/
}
