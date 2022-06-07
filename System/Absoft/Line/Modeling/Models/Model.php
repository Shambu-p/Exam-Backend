<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 2/28/2021
 * Time: 12:49 PM
 */

namespace Absoft\Line\Modeling\Models;


use Absoft\Line\DbConnection\Database;
use Absoft\Line\FaultHandling\Exceptions\ClassNotFound;
use Absoft\Line\FaultHandling\Exceptions\OperationFailed;

class Model implements ModelInterface
{

    /**
     * @var ModelInterface
     */
    private $model;
    public $MAINS;
    public $TABLE_NAME;
    public $HIDDEN;
    public $DATABASE = "MySql";
    public $DATABASE_NAME = "first";


    public function __construct(){

        switch ($this->DATABASE){

            case "Mongo":
                $this->model = new MongoModel();
                break;
            default:
                $this->model = new SQLModel();
                $this->model->TABLE_NAME = $this->TABLE_NAME;
                $this->model->HIDDEN = $this->HIDDEN;
                $this->model->DATABASE_NAME = $this->DATABASE_NAME;
                $this->model->DATABASE = $this->DATABASE;
                $this->model->MAINS = $this->MAINS;
                $this->model->setDB();
                break;

        }

    }

    public function findRecord($key){
        return $this->model->findRecord($key);
    }

    public function searchRecord(array $condition_array, array $filter_array = [], $other = []){
        $return = $this->model->searchRecord($condition_array, $filter_array, $other);
        return $return;
    }

    public function deleteRecord(array $condition = []){
        return $this->model->deleteRecord($condition);
    }

    public function updateRecord($set, $condition){
        return $this->model->updateRecord($set, $condition);
    }

    public function addRecord(array $values){
        return $this->model->addRecord($values);
    }

    public function getMaxOf($attribute){

        if($this->DATABASE == "MySql"){
            return $this->model->getMaxOf($attribute);
        }
        return -1;

    }

    /**
     * @param array $search_array
     * @param array $other
     * @return array|bool|\PDOStatement
     * @throws OperationFailed
     */
    public function advancedSearch(Array $search_array, $other = []){
        return $this->model->advancedSearch($search_array, $other);
    }

    public function getMinOf($attribute){

        if($this->DATABASE == "MySql"){
            return $this->model->getMinOf($attribute);
        }
        return -1;

    }

    /**
     * @return mixed
     * @throws ClassNotFound
     */
    function getEntity(){
        return $this->model->getEntity();
    }

    public function hasOne($model_name){
        return $this->model->hasOne($model_name);
    }

    public function hasMany($model_name){
        return $this->model->hasMany($model_name);
    }

    /**
     * @param array $values
     * @return boolean
     */
    public function addMultiple(array $values){
        return $this->model->addMultiple($values);
    }

    public function setDB($db = null){
        $this->DATABASE == "MySql" ? $this->model->setDB($db) : null;
    }

    /**
     * @return Database|null
     */
    public function getDB(){
        return $this->DATABASE == "MySql" ? $this->model->getDB() : null;
    }

    public function beginTransaction($db = null){
        return $this->DATABASE == "MySql" ? $this->model->beginTransaction($db) : null;
    }

    public function commit(){
        return $this->DATABASE == "MySql" ? $this->model->commit() : null;
    }

    public function rollback(){
        return $this->DATABASE == "MySql" ? $this->model->rollback() : null;
    }

    /**
     * @return int|string|null
     */
    public function lastInsertId(){
        return $this->DATABASE == "MySql" ? $this->model->lastInsertId() : null;
    }

    public function getModel(){
        return $this->model;
    }

}
