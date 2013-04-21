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
        print_r(
            $db->execute('INSERT INTO test () VALUES (?, ?)',array(1, 'Hello'))
        );
        print_r(
            $db->execute('INSERT INTO test () VALUES (?, ?)',array(1, 'World'))
        );
    }

    public function testSelectAll() {
        $db = new DB('mysql:host=localhost;dbname=tests', 'root');
        print_r(
            $db->getAll('SELECT test')
        );
    }

    public function testSelectOne() {
        $db = new DB('mysql:host=localhost;dbname=tests', 'root');
        print_r(
            $db->getOne('SELECT id WHERE data = ?', array('World'))
        );
    }

    public function testSelectRow() {
        $db = new DB('mysql:host=localhost;dbname=tests', 'root');
        print_r(
            $db->getRow('SELECT id WHERE data = ?', array('Hello'))
        );
    }

}
