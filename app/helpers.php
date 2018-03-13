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
    function cdn($url, $origin='https://low.bi', $prefix='https://o68g2cu3w.qnssl.com')
    {
        if (App::environment('local'))
            return $url;
        if (strpos($origin, $url)===0)
            return str_replace($origin, $prefix, $url);
        if (strpos('//', $url) === 0)
            return $url;
        $url = ltrim($url, '/');
        return $prefix . '/' . $url;
    }
}