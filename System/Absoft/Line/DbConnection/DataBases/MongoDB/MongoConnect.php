<?php
/**
 * created by Aboma Teshome
 * ID: A/UR4419/09
 * Date: Monday January 18 2021
 * WebProgramming Assignment
 */
namespace Absoft\Line\DbConnection\DataBases\MongoDB;

use Absoft\Line\DbConnection\QueryConstruction\SQL\QueryConstructor;
use Absoft\Line\DbConnection\DataBases\Connection;
use Absoft\Line\DbConnection\QueryConstruction\Query;
use Absoft\Line\DbConnection\QueryConstruction\SQL\Update;
use MongoDB\Driver\Exception\Exception;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query as MongoQuery;
use MongoDB\Driver\BulkWrite;

class MongoConnect implements Connection {

    public $HOST_ADDRESS = null;
    public $DB_NAME = null;
    private $DB_USERNAME = null;
    private $DB_PASSWORD = null;

    function __construct(array $db_info){

        $this->HOST_ADDRESS = $db_info['HOST_ADDRESS'];
        $this->DB_NAME = $db_info['DB_NAME'];
        $this->DB_USERNAME = $db_info['DB_USERNAME'];
        $this->DB_PASSWORD = $db_info['DB_PASSWORD'];

    }

    function getConnection(){

        //$this->DB = new Manager("mongodb://localhost:27017");
        try{

            return new Manager("mongodb://" . $this->HOST_ADDRESS);

        }catch(\Exception $ex){

            return null;

        }

    }

    function readRecord($table, $filter){

        $db = $this->getConnection();
        $query = new MongoQuery($filter);

        try {

            $result = $db->executeQuery($this->DB_NAME . "." . $table, $query);
            return $result->toArray();

        } catch (Exception $e) {

            return [];

        }

    }

    function createRecord($table, $create_array){

        $db = $this->getConnection();
        $bulk = new BulkWrite();
        $bulk->insert($create_array);
        $result = $db->executeBulkWrite($this->DB_NAME.".".$table, $bulk);

        if(sizeof($result->getWriteErrors())){
            return false;
        }
        else{
            return true;
        }

    }

    function deleteRecord($table, $filter){

        $db = $this->getConnection();
        $bulk = new BulkWrite();
        $bulk->delete($filter);
        $result = $db->executeBulkWrite($this->DB_NAME.".".$table, $bulk);

        if(sizeof($result->getWriteErrors())){
            return false;
        }
        else{
            return true;
        }

    }

    function updateRecord($table, $filter, $set){

        $db = $this->getConnection();
        $bulk = new BulkWrite();
        $bulk->update($filter, $set, ['multi' => true]);
        $result = $db->executeBulkWrite($this->DB_NAME.".".$table, $bulk);

        if(sizeof($result->getWriteErrors())){
            return false;
        }
        else{
            return true;
        }
    }

    function execute(Query $query)
    {
        // TODO: Implement execute() method.
        $db = $this->getConnection();
        $values = $query->getQuery();
        try {

            $result = $db->executeQuery($this->DB_NAME . "." . $values["table"], $query->getQuery());
            return $result->toArray();

        } catch (Exception $e) {

            return [];

        }

    }

    function executeUpdate(Query $query)
    {
        // TODO: Implement executeUpdate() method.
        $db = $this->getConnection();
        $values = $query->getValues();
        $result = $db->executeBulkWrite($this->DB_NAME.".".$values["table"], $query->getQuery());

        if(sizeof($result->getWriteErrors())){
            return false;
        }
        else{
            return true;
        }

    }

    function executeFetch(Query $query)
    {
        // TODO: Implement executeFetch() method.

        try {

            $db = $this->getConnection();
            $values = $query->getValues();
            $result = $db->executeQuery($this->DB_NAME . "." . $values, $query->getQuery());
            return $result->toArray();

        } catch (Exception $e) {

            return [];

        }

    }

    function executeInReturn(Query $query)
    {
        // TODO: Implement executeInReturn() method.
        return [];
    }

    function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    function commit()
    {
        // TODO: Implement commit() method.
    }

    function rollback()
    {
        // TODO: Implement rollback() method.
    }
}
