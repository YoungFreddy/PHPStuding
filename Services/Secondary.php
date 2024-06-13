<?php

class Secondary
{
    public static function check(string $name):bool
    {
        if (preg_match('/[^-_A-Za-z0-9]+/', $name) || iconv_strlen($name) > 200) {
            return true;
        } else return false;
    }

}