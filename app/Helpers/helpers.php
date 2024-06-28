<?php

if (!function_exists('ucReplaceUnderscoreToSpace')) {
    function ucReplaceUnderscoreToSpace($string)
    {
        return ucwords(str_replace('_', ' ', $string));
    }
}


if (!function_exists('ucReplaceDotToSpace')) {
    function ucReplaceDotToSpace($string)
    {
        return ucwords(str_replace('.', ' ', $string));
    }
}
