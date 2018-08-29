<?php
if (!function_exists('escape_like')) {
    /**
     * @param $string
     * @return mixed
     */
    function escape_like($string)
    {
        $search = array('%', '_');
        $replace = array('\%', '\_');
        return str_replace($search, $replace, $string);
    }
}

if (!function_exists('cdn')) {
    /**
     * @param $uri
     * @param string $prefix
     * @return string
     *
     * Add cdn prefix to $uri
     */
    function cdn($uri, $prefix = null)
    {
        if ($prefix===null)
            $prefix=config('qiniu.prefix');
        if (App::environment('local'))
            return $uri;
        $url = ltrim($uri, '/');
        return $prefix . '/' . $url;
    }
}

if (!function_exists('cdn_replace')) {
    /**
     * @param $content
     * @param null $prefix
     * @return mixed
     *
     * Replace urls to cdn urls
     */
    function cdn_replace($content, $prefix = null)
    {
        if (App::environment('prod')) {
            if ($prefix===null)
                $prefix=config('qiniu.prefix');
            $base =  env('APP_URL');

            $base = trim($base, '/');
            $prefix = trim($prefix, '/');

            $targets = [config('filesystems.disks.public.url')];
            foreach ($targets as $target) {
                $replace = str_replace($base, $prefix, $target);
                $content = str_replace($target, $replace, $content);
            }
        }
        return $content;
    }
}


if (!function_exists('str_starts_with')) {
    /**
     * @param $str
     * @param $needle
     * @return bool
     *
     * Determine if $str starts with $needle
     */
    function str_starts_with($str, $needle)
    {
        return strpos($str, $needle) === 0;
    }
}

if (!function_exists('str_ends_with')) {
    /**
     * @param $str
     * @param $needle
     * @return bool
     *
     * Determine if $str ends with $needle
     */
    function str_ends_with($str, $needle)
    {
        return strrpos($str, $needle) === strlen($str) - strlen($needle);
    }
}

if (!function_exists('proxy_gravatar')) {
    /**
     * @param $str
     * @param $prefix
     * @return mixed
     *
     * replace gravatar url with custom proxy
     */
    function proxy_gravatar($str, $prefix='https://gravatar.low.bi')
    {
        $pattern = '/(.*)http(s*):\/\/[A-z]+.gravatar.com(.*)/';
        return  preg_replace($pattern, '$1'.$prefix.'$3', $str);
    }
}

if (!function_exists('fix_url')) {
    function fix_url($url) {
        if (strpos($url, 'http') !== 0) {
            $url =  'http://' . $url;
        }
        return $url;
    }
}
