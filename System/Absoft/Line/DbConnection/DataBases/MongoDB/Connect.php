<?php
/**
 * created by Abnet Kebede
 * Date: Tuesday January 11 2013
 * for webDevelopment Assignment
 */
namespace Absoft\DbConnection\DataBases\MongoDB;

use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\BulkWrite;

class Connect{

    private $DB;

    /**
     * Connect constructor.
     * @param $db string
     * is database name to manipulate
     */
    function __construct($db)
    {
        $this->DB = $db;
    }

    /**
     * Connection will be created here
     * this method only works on localhost and if the settings are default setting
     * if port is changed you should chage the port here also
     */
    function getConnection(){
        return new Manager("mongodb://localhost:27017");
    }

    /**
     * @param $table string
     *  is name of collection
     * @param $filter array
     * is condition to tell mongo which row to fetch
     * @return array
     * return the records available according to the filter array
     * @throws \MongoDB\Driver\Exception\Exception
     */
    function read($table, $filter){
        $connection = $this->getConnection();
        $query = new Query($filter);
        $result = $connection->executeQuery($this->DB.".".$table, $query);
        return $result->toArray();
    }

    /**
     * @param $table string
     * is collection name
     * @param $create_array array
     * is data to be write on database
     * @return boolean
     * if the execution was successful then it will return true
     * else it will return false
     */
    function create($table, $create_array){
        $connection = $this->getConnection();
        $bulk = new BulkWrite();
        $bulk->insert($create_array);
        $result = $connection->executeBulkWrite($this->DB.".".$table, $bulk);

        if(sizeof($result->getWriteErrors())){
            return false;
        }
        else{
            return true;
        }
    }

    /**
     * this is delete function to delete record in mongodb
     * @param $table string
     * is collection name
     * @param $filter array
     * is condition array it tells which record to delete
     * @return boolean
     * this method will return bolean
     * if execution were successful it will return true
     * else it will return false
     */
    function delete($table, $filter){
        $connection = $this->getConnection();
        $bulk = new BulkWrite();
        $bulk->delete($filter);
        $result = $connection->executeBulkWrite($this->DB.".".$table, $bulk);

        if(sizeof($result->getWriteErrors())){
            return false;
        }
        else{
            return true;
        }
    }

    /**
     * this is update function to update record in mongodb
     * @param $table string
     * is collection name
     * @param $filter array
     * is condition array it specify which recod to update
     * @param $set array
     * is array which will be written on mongo
     * @return boolean
     * this method will return boolean 
     * if the execution were successful it will return ture
     * else it will return false
     */
    function update($table, $filter, $set){

        $connection = $this->getConnection();
        $bulk = new BulkWrite();
        $bulk->update($filter, $set, ['multi' => true]);
        $result = $connection->executeBulkWrite($this->DB.".".$table, $bulk);

        if(sizeof($result->getWriteErrors())){
            return false;
        }
        else{
            return true;
        }
    }

}
