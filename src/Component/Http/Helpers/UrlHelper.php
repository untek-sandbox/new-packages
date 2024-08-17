<?php

namespace Untek\Component\Http\Helpers;

use GuzzleHttp\Psr7\Query;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Component\Web\Html\Helpers\Url;
use function GuzzleHttp\Psr7\parse_query;

class UrlHelper
{

    public static function requestUri(): string
    {
        global $_SERVER;
        $baseUrl = explode('?', $_SERVER['REQUEST_URI'])[0];
        return $baseUrl;
    }

    /**
     * Returns a value indicating whether a URL is relative.
     * A relative URL does not have host info part.
     * @param string $url the URL to be checked
     * @return bool whether the URL is relative
     */
    protected static function isRelative($url): bool
    {
        return strncmp($url, '//', 2) && strpos($url, '://') === false;
    }

    public static function generateUrlFromParams(array $data): string {

//    scheme - e.g. http
//    host
//    port
//    user
//    pass
//    path
//    query - after the question mark ?
//    fragment - after the hashmark #

        // $url = 'http://usr:pss@example.com:81/mypath/myfile.html?a=b&b[]=2&b[]=3#myfragment';

        $url = '';
        if(!empty($data['scheme'])) {
            $url .=  $data['scheme'] . '://';
        }
        if(!empty($data['user'])) {
            $url .=  $data['user'];
            if(!empty($data['pass'])) {
                $url .=  ':' . $data['pass'];
            }
            $url .= '@';
        }
        if(!empty($data['host'])) {
            $url .=  $data['host'];
        }
        if(!empty($data['port'])) {
            $url .=  ':' . $data['port'];
        }
        if(!empty($data['path'])) {
            $data['path'] = ltrim($data['path'], '/');
            $url .=  '/' . $data['path'];
        }
        if(!empty($data['query'])) {
            if(is_array($data['query'])) {
                $data['query'] = http_build_query($data['query']);
            }
            $url .=  '?' . $data['query'];
        }
        if(!empty($data['fragment'])) {
            $url .=  '#' . $data['fragment'];
        }

        return $url;
    }

    /*public static function splitUri(string $uri): array
    {
        $uri = trim($uri, '/');
        $uriSegments = explode('/', $uri);
        return $uriSegments;
    }*/

    public static function parse($url, $key = null)
    {
        $r = parse_url($url);
        if (!empty($r['query'])) {
//            $r['query'] = parse_query($r['query']);
            $r['query'] = Query::parse($r['query']);
        }
        if ($key) {
            return ArrayHelper::getValue($r, $key);
        } else {
            return $r;
        }
    }

    /*public static function currentDomain()
    {
        return self::extractDomainFromUrl($_SERVER['HTTP_HOST']);
    }

    public static function baseDomain($domain)
    {
        $arr = explode('.', $domain);
        while (count($arr) > 2) {
            array_shift($arr);
        }
        return implode('.', $arr);
    }

    public static function extractDomainFromUrl($url)
    {
        $domainArr = explode(":", $url);
        $domain = count($domainArr) > 1 ? $domainArr[1] : $domainArr[0];
        $segmentArr = explode(":", $domain);
        $domain = trim($segmentArr[0], '/');
        return $domain;
    }

    public static function isAbsolute($url)
    {
        $pattern = "/^(?:ftp|https?|feed)?:?\/\/(?:(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*
        (?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:
        (?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?]
        (?:[\w#!:\.\?\+\|=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?$/xi";
        return (bool)preg_match($pattern, $url);
    }

    public static function generateUrl($url, $getParameters = null)
    {
        $url = Url::to([$url]);
        if (!empty($getParameters)) {
            $get = self::generateGetParameters($getParameters);
            if (!empty($get)) {
                $url .= '?' . $get;
            }
        }
        return $url;
    }

    public static function generateGetParameters($params)
    {
        $result = '';
        foreach ($params as $name => $value) {
            $result .= "&$name=$value";
        }
        $result = trim($result, '&');
        return $result;
    }*/
}
