<?php

namespace Untek\Component\Http\Helpers;

\Untek\Component\Dev\Helpers\DeprecateHelper::hardThrow();

class IpHelper
{

    public static function getIpByUrl(string $url)
    {
        $parsedUrl = parse_url($url);
        return gethostbynamel($parsedUrl['host']);
    }
}
