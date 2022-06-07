<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 3/2/2020
 * Time: 3:04 PM
 */

namespace Absoft\Line\DbConnection;

use Absoft\Line\DbConnection\DataBases\MongoDB\MongoConnect;
use Absoft\Line\DbConnection\DataBases\MsSQL\MsSql;
use Absoft\Line\DbConnection\DataBases\MySQL\MySql;
use Absoft\Line\DbConnection\DataBases\Connection;
use Absoft\Line\DbConnection\QueryConstruction\Query;
use Application\conf\DBConfiguration;

class Database implements Connection {

    /** @var Connection  */
    public $subject;

    public $configuration_array;

    /** @var \PDO|null  */
    public static $mysql = null;

    /** @var \PDO|null  */
    public static $mssql = null;

    public static $mongo = null;



    function __construct($srv_name, $db_name){
        

        $this->configuration_array = DBConfiguration::$conf;

        if(sizeof($this->configuration_array) > 0){

            if(isset($this->configuration_array[$srv_name][$db_name])){

                switch ($srv_name) {
                    case "MySql":
                        $this->subject = new MySql($this->configuration_array[$srv_name][$db_name]);
                        break;
                    case "MsSql":
                        $this->subject = new MsSql($this->configuration_array[$srv_name][$db_name]);
                        break;
                    case "Mongo":
                        $this->subject = new MongoConnect($this->configuration_array[$srv_name][$db_name]);
                        break;
                }

            }

        }

    }

    function getConnection(){
        return $this->subject->getConnection();
    }

    function execute(Query $query){
        return $this->subject->execute($query);
    }

    function executeUpdate(Query $query){
        return $this->subject->executeUpdate($query);
    }

    function executeInReturn(Query $query){
        return $this->subject->executeInReturn($query);
    }

    function executeFetch(Query $query)
    {
        return $this->subject->executeFetch($query);
    }

    /**
     * @param \PDO $con
     */
    static function setMysql(\PDO $con){
        Database::$mysql = Database::$mysql ? Database::$mysql : $con;
    }

    /**
     * @param \PDO $con
     */
    static function setMssql(\PDO $con){
        Database::$mssql = Database::$mssql ? Database::$mssql : $con;
    }

    static function setMongo($con){
        Database::$mongo = Database::$mongo ? Database::$mongo : $con;
    }

    function beginTransaction(){
        return $this->subject->beginTransaction();
    }

    function commit(){
        return $this->subject->commit();
    }

    function rollback(){
        return $this->subject->rollback();
    }

    /**
     * @return int|string
     */
    function lastInsertId(){
        return $this->subject->lastInsertId();
    }

}
