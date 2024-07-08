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

if (!function_exists('ucReplaceSpaceToUnderscore')) {
    function ucReplaceSpaceToUnderscore($string)
    {
        return ucwords(str_replace(' ', '_', $string));
    }
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($string)
    {
        $angka = (float)$string;
        $formattedNumber = number_format($angka, 2, ',', '.');
        return 'Rp. ' . $formattedNumber;
    }
}
