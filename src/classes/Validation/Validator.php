<?php

namespace PhpMysql\Validation;
class Validator
{
    public static function string(string $string, int $min = 1, int $max = 1000): bool
    {
        $string = trim($string);
        return (strlen($string) >= $min && strlen($string) <= $max);
    }

    public static function password(string $password): bool {
        if (mb_strlen($password) >= 8 &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password)
        ) {
            return true;
        }
        return false;
    }

}