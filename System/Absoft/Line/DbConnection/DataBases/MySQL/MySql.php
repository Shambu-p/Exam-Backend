<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 12/7/2019
 * Time: 9:15 AM
 */

namespace Absoft\Line\DbConnection\DataBases\MySQL;

use Absoft\App\Security\IpCheck;
use Absoft\Line\DbConnection\Database;
use Absoft\Line\DbConnection\DataBases\Connection;
use Absoft\Line\DbConnection\QueryConstruction\Query;
use Absoft\Line\DbConnection\QueryConstruction\SQL\Selection;
use Absoft\Line\DbConnection\QueryConstruction\SQL\Update;
use Absoft\Line\FaultHandling\Errors\DBConnectionError;
use Absoft\Line\FaultHandling\Errors\ExecutionError;
use Absoft\App\Files\DirConfiguration;
use \PDOStatement;

class MySql implements Connection {

    public $HOST_ADDRESS = null;
    public $DB_NAME = null;
    private $DB_USERNAME = null;
    private $DB_PASSWORD = null;
    private $DB = null;

    function __construct(array $db_info){

        $this->HOST_ADDRESS = $db_info['HOST_ADDRESS'];
        $this->DB_NAME = $db_info['DB_NAME'];
        $this->DB_USERNAME = $db_info['DB_USERNAME'];
        $this->DB_PASSWORD = $db_info['DB_PASSWORD'];

//        Database::$mysql = (Database::$mysql == null) ? $this->getConnection() : Database::$mysql;
        $this->DB = $this->getConnection();

    }

    /**
     * @return \PDO|null
     */
    function getConnection(){

        try{

            $dns = "mysql:host=". $this->HOST_ADDRESS.";dbname=".$this->DB_NAME;

            //$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return new \PDO($dns, $this->DB_USERNAME, $this->DB_PASSWORD);

        }catch(\Exception $e){

            new DBConnectionError("mysql", $this->HOST_ADDRESS, $this->DB_NAME, $e->getMessage());

            return null;

        }

    }

    /**
     * @param Query $query
     * @return null
     */
    function executeFetch(Query $query){

        $data = [];
//        $db = Database::$mysql;
        $db = $this->DB;
        $status = null;
        $statement = null;

        //print $query->getQuery();
        $statement = $db->prepare($query->getQuery());

        if(sizeof($query->getValues()) == 0){
            $status = $statement->execute();
        }else{
            $status = $statement->execute($query->getValues());
        }

        if($status){

            while($row = $statement->fetch( \PDO:: FETCH_ASSOC)){
                $data[] = $row;
            }

            return $data;

        }else{

            $this->generateLog($query->getQuery(), IpCheck::clientIp(), IpCheck::clientIp());
            new ExecutionError($statement->errorInfo()[2]);

        }

        return $data;

    }

    /**
     * @param Query $query
     * @return array|bool|PDOStatement
     */
    function executeInReturn(Query $query){

        $db = $this->DB;
//        $db = Database::$mysql;
        $status = null;
        $statement = null;

//        print $query->getQuery();
//        print_r($query->getValues());
        $statement = $db->prepare($query->getQuery());

        if(sizeof($query->getValues())){
            $status = $statement->execute($query->getValues());
        }else{
            $status = $statement->execute();
        }

        if($status){
            return $statement;
        }else{

            $this->generateLog($query->getQuery(), IpCheck::clientIp(), IpCheck::clientIp());
            //new ExecutionError($statement->errorInfo()[2]);

        }

        return [];

    }

    /**
     * @param Query $sql
     * @return null
     */
    function executeUpdate(Query $sql){

        $db = $this->DB;
//        $db = Database::$mysql;
        $status = null;
        $statement = null;

        $statement = $db->prepare($sql->getQuery());

        if(sizeof($sql->getValues()) == 0){

            $status = $statement->execute();

        }else{

            $status = $statement->execute($sql->getValues());

        }

        if($status){

            return true;

        }else{

            $this->generateLog($sql->getQuery(), "localhost", "localhost");
            new ExecutionError($statement->errorInfo()[2]);

        }

        return false;

    }

    /**
     * @param $sql
     * @return bool
     */
    function execute(Query $sql){

        $db = $this->DB;
//        $db = Database::$mysql;
        $status = null;
        $statement = null;

        $statement = $db->prepare($sql->getQuery());

        if(sizeof($sql->getValues()) == 0){

            $status = $statement->execute();

        }else{

            $status = $statement->execute($sql->getValues());

        }

        if($status){

            return $status;

        }else{

            $this->generateLog($sql->getQuery(), "localhost", "localhost");
            new ExecutionError($statement->errorInfo()[2]);

        }

        return $status;

    }

    function generateLog($sql, $srvname, $ip){

        try{

            $nd = new \DateTime();
            $time = $nd->format("H:i:s");
            $date = $nd->format("Y-m-j");
            $log = "$ip, $srvname, $sql, $time, $date \n";
            file_put_contents(DirConfiguration::$_main_folder."/failure_log.txt", $log, FILE_APPEND);
            return 1;

        }catch(\Exception $e){

            return $e->getMessage();

        }

    }

    function beginTransaction(){
        return $this->DB->beginTransaction();
//        return Database::$mysql->beginTransaction();
    }

    function commit(){
        return $this->DB->commit();
//        return Database::$mysql->commit();
    }

    function rollback(){
        return $this->DB->rollBack();
//        return Database::$mysql->rollBack();
    }

    /**
     * @return int|string
     */
    function lastInsertId(){
        return $this->DB->lastInsertId();
//        return Database::$mysql->lastInsertId();
    }

}
