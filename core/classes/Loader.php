<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 08.12.2018
 * Time: 21:01
 */

namespace Core\Classes;


use Core\Utils\Logger;

class Loader
{
    function __construct()
    {
        spl_autoload_register(array($this, 'loader'));
    }

    private function loader(string $class) : void
    {
        $path = $this->genPath($class);
        if (file_exists($path))
            require($path);
        else
            throw new \Exception("Класс не найден! Имя класса: {$class}");
    }

    private function genPath(string $class) : string
    {
        $class = explode('\\', $class);
        $class_name = array_pop($class);
        $path = '';
        foreach($class as $element)
            $path .= strtolower($element).'/';
        $path .= $class_name.'.php';
        return $path;
    }
}