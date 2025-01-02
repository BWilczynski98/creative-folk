<?php

namespace PhpMysql\Validation;
class Validator
{
    public static function string(string $string, int $min = 1, int $max = 1000): bool
    {
        $string = trim($string);
        return (strlen($string) >= $min && strlen($string) <= $max);
    }

}