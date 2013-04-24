<?php namespace Lamb\PDOWrap;

use PDO;
use PDOException;

class DB {

    protected $connection;

    public function __construct($config) {
	if (is_array($config)) {
		extract($config);
	} else if (file_exists($config)) {
		$config = require($config);
		extract($config);
	} else {
		throw new Exception('Invalid Configuration');
	}

	if (!$options) { $options = array(); }

        $this->connection = &ConnectionBag::get($dsn, $username, $password, $options);
    }

    public function disconnect() {
        $this->connection = null;
    }

    public function &getDbh() {
        return $this->connection;
    }

    protected function getResult($sql, $parameters) {
        $queryAttempts = 0;

        do {
            $retrying = false;
            try {
                $sth = $this->connection->prepare($sql);
                $result = $sth->execute($parameters);
            } catch (PDOException $e) {
                $pdoDriver = $this->connection->getAttribute(PDO::ATTR_DRIVER_NAME);
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
        $this->connection->setAttribute(PDO::ATTR_AUTOCOMMIT, TRUE);
    }

    public function disableAutoCommit() {
        $this->connection->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
    }

    public function enableDebug() {
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function disableDebug() {
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    public function setFetchMode($fetchMode = null) {
        $fetchMode = strtolower($fetchMode);

        switch ($fetchMode) {
            case 'both':
                $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
                break;
            case 'num':
                $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
                break;
            case 'assoc':
                $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                break;
            default:
                $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    }

    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    public function commitTransaction() {
        return $this->connection->commit();
    }

    public function rollbackTransaction() {
        return $this->connection->rollBack();
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

        return $sth;
    }

    public function fetchRow($sth) {
        $row = $sth->fetch();

        if ($row) {
            return $row;
        } else {
            $sth->closeCursor();
            unset($sth);
        }
    }

}
