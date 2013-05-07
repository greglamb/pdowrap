<?php

use Lamb\PDOWrap\DB;

class DBTest extends PHPUnit_Framework_TestCase {

    protected $db;

    public function testArrayConnect() {
        $config = array(
                'dsn' => 'mysql:host=localhost;dbname=tests'
                'username' => 'root'
            );
        $this->db = new DB($config);
        $this->assertInstanceOf($this->db->&getDbh(), PDO);
    }

    public function testFileConnect() {
        $this->db = new DB(__DIR__.'/TestConfig.php', 'TravisCI');
        $this->assertInstanceOf($this->db->&getDbh(), PDO);
    }

    public function testCreateTable() {
        $result = $this->db->execute('
                    CREATE TABLE test (
                     id INT,
                     data VARCHAR(100)
                    );
            ');
        $this->assertTrue($result);
    }

    public function testInsert() {
        $result = $this->db->execute('INSERT INTO test () VALUES (?, ?)',array(1, 'Hello'));
        $this->assertTrue($result);
        $result = $this->db->execute('INSERT INTO test () VALUES (?, ?)',array(2, 'World'));
        $this->assertTrue($result);
    }

    public function testSelectAll() {
        $this->db->setFetchMode('assoc');
        $expected = array(
            array('id' => 1, 'data' => 'Hello')
            ,array('id' => 2, 'data' => 'World')
        );
        $actual = $this->db->getAll('SELECT id, data FROM test');
        $this->assertEquals($expected, $actual);
    }

    public function testSelectOne() {
        $expected = 2;
        $actual = $this->db->getOne('SELECT id FROM test WHERE data = ?', array('World'));
        $this->assertEquals($expected, $actual);
    }

    public function testSelectRow() {
        $this->db->setFetchMode('assoc');
        $expected = array('id' => 1, 'data' => 'Hello');
        $actual = $this->db->getRow('SELECT id, data FROM test WHERE data = ?', array('Hello'));
        $this->assertEquals($expected, $actual);
    }

    public function testFetch() {
        $this->db->setFetchMode('assoc');

        $query = $this->db->query('SELECT id, data FROM test');

        $expected = array('id' => 1, 'data' => 'Hello');
        $result = $query->fetchRow();
        $this->assertEquals($expected, $actual);

        $expected = array('id' => 2, 'data' => 'World');
        $result = $query->fetchRow();
        $this->assertEquals($expected, $actual);
    }

}
