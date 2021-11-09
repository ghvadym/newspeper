<?php

namespace inc;

class Validator
{
    static function getEmptyKeys(array $data): array
    {
        return array_keys(array_filter($data, function ($val) {
            return $val === '';
        }, '1'));
    }

    static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    static function checkIfEmailIsUnique($email): bool
    {
        return count(DataBase::checkEmailExist($email)) > 0;
    }

}
