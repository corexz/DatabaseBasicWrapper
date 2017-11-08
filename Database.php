<?php

include('config/config.php');
include(CONFIG.'database_config.php');

class Database extends PDO
{
    private $host       = HOST;
    private $dbname     = DBNAME;
    private $username   = USERNAME;
    private $pass       = PASSWORD;
    private $charset    = CHARSET;

    private $conn;
    private $stmt;
    private $error;


/**
 * [__construct description]
 */
    public function __construct()
    {
        $options  = array( PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                                PDO::ATTR_EMULATE_PREPARES   => false,
                                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_PERSISTENT         => true
                                );
        try {
            $this -> conn = new PDO(
                "mysql:host=".$this -> host.";dbname=".$this -> dbname.";charset=".$this -> charset,
                $this -> username,
                $this-> pass,
                $options
                );
        } catch (PDOException $e) {
            $this -> error =  $e -> getMessage();
        }
    }


/**
 * [query description]
 * @param  [type] $sql [description]
 * @return [type]      [description]
 */
    public function query($sql)
    {
        return $this -> stmt = $this-> conn -> prepare($sql);
    }

/**
 * [bind description]
 * @param  [type] $param [description]
 * @param  [type] $value [description]
 * @param  [type] $type  [description]
 * @return [type]        [description]
 */
    public function bind($param, $value, $type=null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break ;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break ;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break ;
                default:
                    $type = PDO::PARAM_STR;
                    break ;
            }
        }
        return $this -> stmt -> bindValue($param, $value, $type);
    }



    /**
     * [execute description]
     * @return [type] [description]
     */
    public function execute()
    {
        return $this -> stmt ->execute();
    }

/**
 * [resultset description]
 */
    public function resultset()
    {
        $this -> execute();
        return $this -> stmt -> fetchAll();
    }
/**
 * [rowCount description]
 * @return [type] [description]
 */
    public function rowCount()
    {
        return $this -> stmt -> rowCount();
    }
}
