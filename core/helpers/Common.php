<?php

namespace Import\Helpers;

class Common
{
    /**
     * @return bool
     */
    static function isPost()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return bool|string
     */
    static function getPathUrl()
    {
        $pathUrl = $_SERVER['REQUEST_URI'];

        if($position = strpos($pathUrl, '?'))
        {
            $pathUrl = substr($pathUrl, 0, $position);
        }

        return $pathUrl;
    }

   static function replacePath( $path ){

       if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

            $path = str_replace('/', '\\'.'\\', $path);

        } else {

            $path = $path;
        }

        return $path;

   }
}
?>