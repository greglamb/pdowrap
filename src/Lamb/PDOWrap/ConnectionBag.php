<?php namespace Lamb\PDOWrap;

use PDO;

class ConnectionBag {

    static private $dbh = array();

    static private $options = array(
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
        PDO::ATTR_STATEMENT_CLASS => array('stdClass', array())
    );

    private function __construct() { }

    public static function &get($dsn, $username = null, $password = null, $options = array())
    {
        $id = md5(serialize(func_num_args()));

        if (!isset(self::$dbh[$id])) {
            $options = array_diff_key(self::$options, $options) + $options;
            self::$dbh[$id] = new PDO($dsn, $username, $password, $options);
        }

        return self::$dbh[$id];
    }

}
