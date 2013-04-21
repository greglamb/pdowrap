<?php namespace Lamb\PdoWrap;

class DB {

    protected $connection;

    public function __construct($dsn, $options = null) {
        $this->connection = ConnectionBag::get($dsn, $options);
    }

    protected function query($sql, $parameters) {

    }

    public function getOne($sql, $parameters = array()) {

    }

    public function getRow($sql, $parameters = array()) {

    }

    public function getAll($sql, $parameters = array()) {

    }

    public function execute($sql, $parameters = array()) {

    }

}