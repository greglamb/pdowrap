<?php namespace Lamb\PDOWrap;

use PDO;
use Exception;
use PDOException;

class DB {

    protected $dbh;

    public function __construct($config) {
    	if (is_array($config)) {
    		extract($config);
    	} else if (file_exists($config)) {
    		$config = require($config);
    		extract($config);
    	} else {
    		throw new Exception('Invalid Configuration');
    	}

        $this->dbh = &ConnectionBag::get($dsn, $username, $password, $options);
    }

    public function disconnect() {
        $this->dbh = null;
    }

    public function &getDbh() {
        return $this->dbh;
    }

    protected function getResult($sql, $parameters) {
        $queryAttempts = 0;

        do {
            $retrying = false;
            try {
                $sth = $this->dbh->prepare($sql);
                $result = $sth->execute($parameters);
            } catch (PDOException $e) {
                $pdoDriver = $this->dbh->getAttribute(PDO::ATTR_DRIVER_NAME);
                if (($queryAttempts < 3) && ($e->getCode() == 1213) && ($pdoDriver == 'mysql')) {
                    $retrying = true;
                    $queryAttempts++;
                } else {
                    throw $e;
                }
            }
        } while ($retrying);

        return array($result, $sth);
    }

    public function enableAutoCommit() {
        $this->dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, TRUE);
    }

    public function disableAutoCommit() {
        $this->dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
    }

    public function enableDebug() {
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function disableDebug() {
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    public function setFetchMode($fetchMode = null) {
        $fetchMode = strtolower($fetchMode);

        switch ($fetchMode) {
            case 'both':
                $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
                break;
            case 'num':
                $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
                break;
            case 'assoc':
                $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                break;
            case 'class':
                $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS);
                break;
            default:
                $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS);
        }
    }

    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    public function commitTransaction() {
        return $this->dbh->commit();
    }

    public function rollbackTransaction() {
        return $this->dbh->rollBack();
    }

    public function getOne($sql, $parameters = array()) {
        list($result, $sth) = $this->getResult($sql, $parameters);

        $fetched = $sth->fetchColumn();
        $sth->closeCursor();
        unset($sth);

        return $fetched;
    }

    public function getRow($sql, $parameters = array()) {
        list($result, $sth) = $this->getResult($sql, $parameters);

        $fetched = $sth->fetch();
        $sth->closeCursor();
        unset($sth);

        return $fetched;
    }

    public function getAll($sql, $parameters = array()) {
        list($result, $sth) = $this->getResult($sql, $parameters);

        $fetched = $sth->fetchAll();
        $sth->closeCursor();
        unset($sth);

        return $fetched;
    }

    public function execute($sql, $parameters = array()) {
        list($result, $sth) = $this->getResult($sql, $parameters);

        $sth->closeCursor();
        unset($sth);

        return $result;
    }

    public function query($sql, $parameters = array()) {
        list($result, $sth) = $this->getResult($sql, $parameters);
        return new StatementHandle($sth);
    }

}
