<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 16.12.2018
 * Time: 13:39
 */

namespace Core\Classes;


class Session
{
    private static $lifetime = 86400;
    private static $cookieName = "cid";
    private static $started = false;

    public static function isCreated () : bool
    {
        return (!empty($_COOKIE[self::$cookieName]) and ctype_alnum($_COOKIE[self::$cookieName])) ? true : false;
    }

    public static function start () : void
    {
        if(!self::$started) {
            if(!empty($_COOKIE[self::$cookieName]) and !ctype_alnum($_COOKIE[self::$cookieName])) {
                unset($_COOKIE[self::$cookieName]);
            }
            session_set_cookie_params (self::$lifetime, '/');
            session_name (self::$cookieName);
            session_start ();
            self::$started = true;
        }
    }

    public static function set(string $name, string $value) : void
    {
        if(self::$started) {
            $_SESSION[$name] = $value;
        }
        else {
            throw new \Exception('You should start Session first');
        }
    }

    public static function get(string $name) : string
    {
        if(self::$started) {
            return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
        }
        else {
            throw new \Exception('You should start Session first');
        }
    }

    public static function del(string $name) : void
    {
        if(self::$started) {
            unset($_SESSION[$name]);
        }
        else {
            throw new \Exception('You should start Session first');
        }
    }

    public static function clear() : void
    {
        if(self::$started) {
            unset($_SESSION);
        }
        else {
            throw new \Exception('You should start Session first');
        }
    }

    public static function destroy() : void
    {
        if(self::$started) {
            self::$started = false;
            unset($_COOKIE[self::$cookieName]);
            setcookie(self::$cookieName, '', 1, '/');
            session_destroy();
        }
        else {
            throw new \Exception('Session is not started!');
        }
    }

    public static function restart() : void
    {
        self::destroy();
        self::start();
    }

    public static function getArray() : array
    {
        if(self::$started) {
            return $_SESSION;
        }
        else {
            throw new \Exception('You should start Session first');
        }
    }

    public static function commit() : void
    {
        if(self::$started) {
            session_write_close();
            self::$started = false;
        }
        else {
            throw new \Exception('You should start Session first');
        }
    }
}