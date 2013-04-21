<?php

use Lamb\PDOWrap\DB;

class DBTest extends PHPUnit_Framework_TestCase {

    public function testSelectVersion() {
        $db = new DB('mysql:host=localhost;dbname=tests', 'root');
        print_r(
            $db->execute('SELECT version()')
        );
    }

    public function testCreateTable() {
        $db = new DB('mysql:host=localhost;dbname=tests', 'root');
        print_r(
            $db->execute('
                    CREATE TABLE test (
                     id INT,
                     data VARCHAR(100)
                    );
            ')
        );
    }

    public function testInsert() {
        $db = new DB('mysql:host=localhost;dbname=tests', 'root');
        $db->execute('INSERT INTO test () VALUES (?, ?)',array(1, 'Hello'));
        $db->execute('INSERT INTO test () VALUES (?, ?)',array(2, 'World'));
    }

    public function testSelectAll() {
        $db = new DB('mysql:host=localhost;dbname=tests', 'root');
        $expected = array(
            array('id' => 1, 'data' => 'Hello')
            ,array('id' => 2, 'data' => 'World')
        );
        $actual = $db->getAll('SELECT id, data FROM test');
        $this->assertEquals($expected, $actual);
    }

    public function testSelectOne() {
        $db = new DB('mysql:host=localhost;dbname=tests', 'root');
        $expected = 2;
        $actual = $db->getOne('SELECT id FROM test WHERE data = ?', array('World'));
        $this->assertEquals($expected, $actual);
    }

    public function testSelectRow() {
        $db = new DB('mysql:host=localhost;dbname=tests', 'root');
        $expected = array('id' => 1, 'data' => 'Hello');
        $actual = $db->getRow('SELECT id, data FROM test WHERE data = ?', array('Hello'));
        $this->assertEquals($expected, $actual);

    }

}
