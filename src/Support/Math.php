<?php

namespace Budgetlens\BolRetailerApi\Support;

class Math
{

    public static function compare($value1, $value2, $comparison)
    {
        switch ($comparison) {
            case "=":
                return $value1 == $value2;
            case "==":
                return $value1 === $value2;
            case "!=":
                return $value1 != $value2;
            case ">=":
                return $value1 >= $value2;
            case "<=":
                return $value1 <= $value2;
            case ">":
                return $value1 >  $value2;
            case "<":
                return $value1 <  $value2;
            default:
                return true;
        }
    }
}
