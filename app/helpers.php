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
     * @param $url
     * @param string $prefix
     * @return string
     */
    function cdn($url, $prefix = 'https://o68g2cu3w.qnssl.com')
    {
        if (App::environment('local'))
            return $url;
        $url = ltrim($url, '/');
        return $prefix . '/' . $url;
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
