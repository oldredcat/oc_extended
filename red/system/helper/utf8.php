<?php

namespace System\Helper;

class Utf8
{
    public static function strlen(string $string): string
    {
        return mb_strlen($string);
    }

    public static function strpos(string $string, string $needle, int $offset = 0): string
    {
        return mb_strpos($string, $needle, $offset);
    }

    public static function strrpos(string $string, string $needle, int $offset = 0): string
    {
        return mb_strrpos($string, $needle, $offset);
    }

    public static function substr(string $string, int $offset, ?int $length = null): string
    {
        return mb_substr($string, $offset, $length);
    }

    public static function strtoupper(string $string): string
    {
        return mb_strtoupper($string);
    }

    public static function strtolower(string $string): string
    {
        return mb_strtolower($string);
    }
}
