<?php namespace Lamb\PDOWrap;

class StatementHandle {
    
    protected $sth;
    
    public function __construct($sth) {
        $this->sth = $sth;
    }
    
    public function fetchRow() {
        $row = $this->sth->fetch();

        if ($row) {
            return $row;
        } else {
            $sth->closeCursor();
            unset($this->sth);
        }
    }
    
    public function &getSth() {
        return $this->sth;
    }
    
}