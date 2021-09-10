<?php
namespace Budgetlens\BolRetailerApi\Support;

class Uri
{
    public static function queryString(array $filters, ?string $keyName = null): string
    {
        $query = '';

        foreach ($filters as $key => $value) {
            if ($query != '') {
                $query .= "&";
            }
            if (is_array($value)) {
                $query .= self::queryString($value, $key);
            } else {
                if (!is_null($keyName)) {
                    $query .= "{$keyName}={$value}";
                } else {
                    $query .= "{$key}={$value}";
                }
            }
        }

        return $query;
    }
}
