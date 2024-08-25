<?php

namespace Untek\Framework\RestApi\Presentation\Http\Symfony\Helpers;

use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\MimeTypes;

class RestApiHelper
{

    const FORM_URLENCODED = 'form-urlencoded';

    public static function getFormat(Request $request): ?string
    {
        $format = null;
        $mimeType = $request->headers->get('Content-Type');
        if ($mimeType) {
            $extensions = (new MimeTypes)->getExtensions($mimeType);
            $format = Arr::first($extensions);
        }
        if ($format == null) {
            $format = self::FORM_URLENCODED;
        }
        return $format;
    }
}