<?php namespace Lamb\PdoWrap;

class ConnectionBag {

    static private $connection = array();

    private function __construct() { }

    public static function get($dsn, $options)
    {
        $id = md5(json_encode(array($dsn, $options)));

        if (!self::$connection[$id]) {

        }

        return self::$connection[$id];
    }

}