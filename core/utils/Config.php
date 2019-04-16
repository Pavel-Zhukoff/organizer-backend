<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 10.12.2018
 * Time: 12:19
 */

namespace Core\Utils;

class Config
{


    public static function load(string $cfgFile) : array
    {
        $path = 'core/config/'.$cfgFile.'.json';
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        }
        else {
            throw new \Exception("Файл настроект не найден!");
        }
    }


}