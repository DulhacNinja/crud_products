<?php

namespace core;

class Utils
{
    static function dd($value)
    {
        var_dump($value);
        die();
    }
}