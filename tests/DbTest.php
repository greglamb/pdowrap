<?php

use Lamb\PDOWrap\DB;

class DBTest extends PHPUnit_Framework_TestCase {

    public function testGetVersion() {
        $db = new DB('mysql:host=localhost;dbname=tests', 'root');
        return $db->execute('SELECT version()');
    }

}
