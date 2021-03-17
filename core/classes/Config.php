<?php

namespace Import\Base;

class Config
{

    /**
      * меняем путь относительно проверенного результата, указывать относительный путь!
      */ 
   static function replacePath( $fileName ){

       if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

            $path = str_replace('/', '\\'.'\\', $_SERVER['DOCUMENT_ROOT']."/import/assets/config/".$fileName);

        } else {

            $path = $_SERVER['DOCUMENT_ROOT']."/import/assets/config/".$fileName;
        }

        return $path;

   }

    /**
     * Метод позоволяет получить содержимое файла 
     */
     public static function file( $fileName ){
         
         $path = self::replacePath( $fileName );
 
         if( is_readable ( $path) ){

               return require_once $path;

         }

    }

}