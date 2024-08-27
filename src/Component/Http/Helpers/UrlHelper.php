<?php

namespace Untek\Component\Http\Helpers;

use GuzzleHttp\Psr7\Query;

class UrlHelper
{

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

    public static function generateUrlFromParams(array $data): string
    {
        $url = '';
        if (!empty($data['scheme'])) {
            $url .= $data['scheme'] . '://';
        }
        if (!empty($data['user'])) {
            $url .= $data['user'];
            if (!empty($data['pass'])) {
                $url .= ':' . $data['pass'];
            }
            $url .= '@';
        }
        if (!empty($data['host'])) {
            $url .= $data['host'];
        }
        if (!empty($data['port'])) {
            $url .= ':' . $data['port'];
        }
        if (!empty($data['path'])) {
            $data['path'] = ltrim($data['path'], '/');
            $url .= '/' . $data['path'];
        }
        if (!empty($data['query'])) {
            if (is_array($data['query'])) {
                $data['query'] = http_build_query($data['query']);
            }
            $url .= '?' . $data['query'];
        }
        if (!empty($data['fragment'])) {
            $url .= '#' . $data['fragment'];
        }
        return $url;
    }

    public static function parse($url, $key = null)
    {
        $r = parse_url($url);
        if (!empty($r['query'])) {
            $r['query'] = Query::parse($r['query']);
        }
        if ($key) {
            return $r[$key] ?? null;
        } else {
            return $r;
        }
    }
}
