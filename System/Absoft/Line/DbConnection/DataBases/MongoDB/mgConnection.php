<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 2/27/2021
 * Time: 11:58 AM
 */

namespace Absoft\DbConnection\DataBases\MongoDB;


use mysqli;

class Connection
{

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

        $mongo = null;
        try {
            $mongo = new \MongoClient('mongodb://' . $this->DB_USERNAME . ':' . $this->DB_PASSWORD . '@' . $this->HOST_ADDRESS);
            $database = $mongo->selectDB($this->DB_NAME);
            $collection = $database->selectCollection("table");
            $collection->update();
        } catch (\MongoConnectionException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
        }

        return $mongo;

    }



}
