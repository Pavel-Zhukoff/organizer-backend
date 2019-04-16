<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 08.12.2018
 * Time: 22:16
 */

namespace Core\Utils;


class Logger
{

    public static function log($a) : void
    {
        echo '<pre>';
        var_dump($a);
        echo '</pre>';
    }

}