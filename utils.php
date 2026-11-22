<?php
function dd($value)
{
    var_dump($value);
    die();
}

/*
 * about/john OR
 * /about/john OR
 * /about/john/
 * are all valid paths and are being normalized to /about/john/
 */
function normalizePath(string $path): string
{
    $path = trim($path, '/');
    $path = "/{$path}";

    // any sequence of 2 or more '/' replaced with a singular '/'
    preg_replace("#[/]{2,}#", '/', $path);
    return $path;
}

function validateStringLength(string $value): bool
{
    return strlen($value) <= MAX_STR_LENGTH;
}

// https://www.php.net/manual/en/function.exif-imagetype.php
// code copied from comment
function is_jpeg(&$pict)
{
    return (bin2hex($pict[0]) == 'ff' && bin2hex($pict[1]) == 'd8');
}

function is_png(&$pict)
{
    return (bin2hex($pict[0]) == '89' && $pict[1] == 'P' && $pict[2] == 'N' && $pict[3] == 'G');
}

// https://stackoverflow.com/questions/19271381/correctly-determine-if-date-string-is-a-valid-date-in-that-format
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && strtolower($d->format($format)) === strtolower($date);
}