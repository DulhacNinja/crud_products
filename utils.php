<?php
    function dd( $value ) {
        var_dump( $value );
        die();
    }

    /*
    * about/john OR
    * /about/john OR
    * /about/john/
    * are all valid paths and are being normalized to /about/john/
    */
    function normalizePath(string $path) : string {
        $path = trim($path, '/');
        $path = "/{$path}";

        // any sequence of 2 or more '/' replaced with a singular '/'
        preg_replace("#[/]{2,}#", '/', $path);
        return $path;
    }