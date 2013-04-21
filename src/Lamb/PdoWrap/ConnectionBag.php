<?php namespace Lamb\PdoWrap;

use PDO;

class ConnectionBag {

    static private $connection = array();

    static private $options = array(
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    private function __construct() { }

    public static function get($dsn, $username, $password, $options)
    {
        $id = md5(serialize(func_num_args()));

        if (!self::$connection[$id]) {
            $options = array_diff_key(self::$options, $options) + $options;
            self::$connection[$id] = new PDO($dsn, $username, $password, $options);
        }

        return self::$connection[$id];
    }

}