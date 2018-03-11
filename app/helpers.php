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
    function cdn($uri)
    {
        $prefix = 'https://o68g2cu3w.qnssl.com';
        $uri = str_replace('\\', '/', $uri);
        while ($uri && $uri[0] === '/')
            $uri = substr($uri, 1);
        return $prefix . '/' . $uri;
    }
}