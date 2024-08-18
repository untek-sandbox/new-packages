<?php

namespace Untek\Component\Http\Helpers;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

class IpHelper
{

    public static function getIpByUrl(string $url)
    {
        $parsedUrl = parse_url($url);
        $ipList = gethostbynamel($parsedUrl['host']);
        $item = [
            'host' => $parsedUrl['host'],
            'ip' => [],
        ];
        if(is_array($ipList)) {
            foreach($ipList as $ip) {
                $item['ip'][] = $ip;
            }
        }
        return $item;
    }
}
