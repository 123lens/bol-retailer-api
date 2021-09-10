<?php
namespace Budgetlens\BolRetailerApi\Support;

class Str
{
    /** @var array */
    protected static $studlyCache = [];

    public static function studly(string $value): string
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    public static function upper(string $value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }
}
