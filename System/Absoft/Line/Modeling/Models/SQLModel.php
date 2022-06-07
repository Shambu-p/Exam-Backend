<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 3/1/2021
 * Time: 11:11 AM
 */

namespace Absoft\Line\Modeling\Models;

use Absoft\App\Files\DirConfiguration;
use Absoft\App\Files\Resource;
use Absoft\Line\DbConnection\Database;
use Absoft\Line\DbConnection\QueryConstruction\SQL\Deletion;
use Absoft\Line\DbConnection\QueryConstruction\SQL\Insertion;
use Absoft\Line\DbConnection\QueryConstruction\SQL\JointSelection;
use Absoft\Line\DbConnection\QueryConstruction\SQL\Selection;
use Absoft\Line\DbConnection\QueryConstruction\SQL\Update;
use Absoft\Line\FaultHandling\Errors\DataOutOfRangeError;
use Absoft\Line\FaultHandling\Errors\EmptyArrayError;
use Absoft\Line\FaultHandling\Exceptions\ClassNotFound;
use Absoft\Line\FaultHandling\Exceptions\OperationFailed;
use Absoft\Line\FaultHandling\Exceptions\ReferenceNotFound;
use Absoft\Line\FaultHandling\FaultHandler;
use Absoft\Line\Modeling\DbBuilders\Builder;
use PDOStatement;

class SQLModel implements ModelInterface
{

    public $MAINS;
    public $TABLE_NAME;
    public $HIDDEN;
    public $DATABASE;
    public $DATABASE_NAME;

    /** @var Database|null  */
    public $DATABASE_OBJECT = null;


    // public function __construct(){
    //     $this->setDB();
    // }

    /**
     * @param Database|null $db
     */
    public function setDB($db = null){
        $this->DATABASE_OBJECT = ($db ? $db : new Database($this->DATABASE, $this->DATABASE_NAME));
    }

    /**
     * @return Database|null
     */
    public function getDB(){
        return $this->DATABASE_OBJECT ? $this->DATABASE_OBJECT : new Database($this->DATABASE, $this->DATABASE_NAME);
    }

    public function beginTransaction($db = null){
        return $this->DATABASE_OBJECT->beginTransaction();
    }

    public function commit(){
        return $this->DATABASE_OBJECT->commit();
    }

    public function rollback(){
        return $this->DATABASE_OBJECT->rollback();
    }

    /**
     * @return int|string
     */
    public function lastInsertId(){
        return $this->DATABASE_OBJECT->lastInsertId();
    }

    public function findRecord($key){

        try{

            $table = $this->getEntity();
            $pk = $this->getEntity()->PRIMARY_KEY;

            $array = [
                [
                    "name" => "$pk",
                    "value" => $key,
                    "equ" => "=",
                    "det" => "and"
                ]
            ];
            $constructor = new Selection($table, [], $array);
            $connection = $this->DATABASE_OBJECT ? $this->DATABASE_OBJECT : new Database($this->DATABASE, $this->DATABASE_NAME);

            $result = $connection->executeFetch($constructor);

            if(sizeof($result) > 0){
                return $result[0];
            }

            return $result;

        } catch (ClassNotFound $e) {
            $e->report();
            return [];
        }

    }

    /**
     * @param array $condition_array
     * @param array $filter_array
     * @param array $order_by
     * @return array|null
     */
    public function searchRecord(array $condition_array, array $filter_array = [], $other = []){

        $condition = [];
        $filter = [];
        //$r_join = null;
        $order = [];

        if(sizeof($condition_array) > 0 || sizeof($filter_array) > 0){

            foreach(array_merge($this->MAINS, $this->HIDDEN) as $key => $value){

                for($count = 0; $count < sizeof($condition_array); $count ++){

                    if(isset($condition_array[$count]) && isset($condition_array[$count]["name"]) && $condition_array[$count]["name"] == $key ){
                        $condition[] = $condition_array[$count];
                    }

                }

                if(in_array($key, $filter_array)){

                    $filter[] = $key;

                }

            }

        }

        $extra = [];

        if(isset($other["order_by"])){

            if(isset($other["order_by"]["att"]) && isset($this->MAINS[$other["order_by"]["att"]])){

                if(isset($other["order_by"]["det"]) && $other["order_by"]["det"] == "1" || $other["order_by"]["det"] == "0"){

                    $extra["order_by"]["att"] = $other["order_by"]["att"];
                    $extra["order_by"]["det"] = $other["order_by"]["det"];

                }

            }

        }
        
        if(isset($other["limit"])){

            if(isset($other["limit"]["start"]) && isset($other["limit"]["length"])){

                $extra["limit"]["start"] = intval($other["limit"]["start"]);
                $extra["limit"]["length"] = intval($other["limit"]["length"]);

            }

        }

        try{
            $con = $this->DATABASE_OBJECT ? $this->DATABASE_OBJECT : new Database($this->DATABASE, $this->DATABASE_NAME);
            return $con->executeFetch(new Selection($this->getEntity(), $filter, $condition, null, $extra));
        } catch (ClassNotFound $e) {
            $e->report();
            return [];
        }

    }

    /**
     * @return Builder
     * @throws ClassNotFound
     */
    function getEntity(){

        $entity_name = 'Application\\Builders\\'.$this->TABLE_NAME;

        if(Resource::checkFile("/apps/Builders/".$this->TABLE_NAME.".php")){
            return new $entity_name;
        }
        else{
            throw new ClassNotFound($entity_name, __FILE__, __LINE__);
        }

    }

    public function deleteRecord(array $condition = []){

        $return = null;

        $my_condition = [];

        if(sizeof($condition) > 0){

            foreach($this->MAINS as $key => $value){

                for($count = 0; $count < sizeof($condition); $count ++){

                    if(isset($condition[$count]["name"]) && $condition[$count]["name"] == $key){

                        $my_condition[] = $condition[$count];

                    }

                }

            }

        }

        $con = $this->DATABASE_OBJECT ? $this->DATABASE_OBJECT : new Database($this->DATABASE, $this->DATABASE_NAME);

        $query = new Deletion();
        $query->deleteRecord($this->TABLE_NAME, $my_condition);

        $return = $con->execute($query);

        if($return){
            return true;
        }

        return false;

    }

    public function updateRecord($set, $condition){

        $condi = [];
        $st = [];
        $size = sizeof($set);
        $size1 = sizeof($condition);

        if($size > 0){

            foreach($this->MAINS as $key => $value){

                if($size1 > 0){

                    for($count = 0; $count < $size1; $count ++){

                        if(isset($condition[$count]["name"]) && $condition[$count]["name"] == $key){

                            $condi[] = $condition[$count];

                        }

                    }

                }

                if(isset($set[$key])){

                    $st[$key] = $set[$key];

                }

            }

            foreach($this->HIDDEN as $key => $value){

                if(isset($set[$key])){

                    $st[$key] = password_hash($set[$key], PASSWORD_DEFAULT);

                }

            }

            $con = $this->DATABASE_OBJECT ? $this->DATABASE_OBJECT : new Database($this->DATABASE, $this->DATABASE_NAME);
            return $con->executeUpdate(new Update($this->TABLE_NAME, $st, $condi));

        }else{

            new EmptyArrayError("There is nothing to update. you should try to fill the array and then try again.");
            return false;

        }

    }

    /**
     * @param array $values
     * @return array|bool
     */
    public function addRecord(array $values){

        $in_values = [];
        $size = sizeof($values);

        if($size > 0 && $size <= (sizeof($this->MAINS) + sizeof($this->HIDDEN))){

            foreach($this->MAINS as $key => $value){

                if(isset($values[$key]) && $values[$key] != ""){

                    $in_values[$key] = $values[$key];

                }

            }

            foreach($this->HIDDEN as $key => $value){

                if(isset($values[$key]) && $values[$key] != ""){

                    $in_values[$key] = password_hash($values[$key], PASSWORD_DEFAULT);

                }

            }

            $con = $this->DATABASE_OBJECT ? $this->DATABASE_OBJECT : new Database($this->DATABASE, $this->DATABASE_NAME);
            return $con->execute(new Insertion($this->TABLE_NAME, $in_values));

        }else{
            new DataOutOfRangeError("The size of array of values to be inserted and the size of table attributes is not compatible");
        }

        return false;

    }

    public function addMultiple(array $values){

        $in_values = [];
        $merged = array_merge($this->MAINS, $this->HIDDEN);
        $merged_size = sizeof($merged);

        foreach($merged as $key => $value){

            $cn = 0;
            foreach($values as $record){

                $size = sizeof($record);

                if($size > 0 && $size <= $merged_size) {

                    if(isset($record[$key]) && $record[$key] != ""){

                        if(isset($this->HIDDEN[$key])){
                            $in_values[$cn][$key] = password_hash($record[$key], PASSWORD_DEFAULT);
                        }else{
                            $in_values[$cn][$key] = $record[$key];
                        }

                    }

                }else{
                    new DataOutOfRangeError("The size of array of values to be inserted and the size of table attributes is not compatible");
                    return false;
                }

                $cn += 1;

            }

        }

        $con = $this->DATABASE_OBJECT ? $this->DATABASE_OBJECT : new Database($this->DATABASE, $this->DATABASE_NAME);
        return $con->execute(new Insertion($this->TABLE_NAME, $in_values, true));

    }

    /**
     * @param $model_name
     * @return mixed
     */
    public function hasOne($model_name){

        try{

            /** @var Model $model */
            $model = new $model_name;

            $reference = $this->getEntity()->getReference($model->TABLE_NAME);
            $value = $this->MAINS[$reference];
            return $model->findRecord($value);

        } catch (ClassNotFound $e) {
            $e->report();
            return [];
        } catch(ReferenceNotFound $e) {
            $e->report();
            return [];
        }

    }

    /**
     * @param array $search_array
     * @param array $other
     * @return array|bool|PDOStatement
     * @throws OperationFailed
     */
    public function advancedSearch(Array $search_array, $other = []){

        $filter = [];
        $condition = [];
        $join = [];
        $extra = [];
        $count = 0;

        foreach(array_merge($this->MAINS, $this->HIDDEN) as $key => $val){

            if(isset($search_array[$key]) && $key != ":join"){

                if(is_array($search_array[$key])){

                    foreach($search_array[$key] as $cond){

                        if(is_array($cond) && isset($cond["value"]) && isset($cond["equ"]) && isset($cond["det"])){

                            $condition[] = [
                                "name" => $this->TABLE_NAME.".".$key,
                                "value" => $cond["value"],
                                "equ" => $cond["equ"],
                                "det" => $cond["det"]
                            ];

                        }

                    }

                }
                else if($search_array[$key] && !is_array($search_array[$key])){

                    $condition[] = [
                        "name" => $this->TABLE_NAME.".".$key,
                        "value" => $search_array[$key],
                        "equ" => "=",
                        "det" => "and"
                    ];

                }

                $filter[$count]["name"] = $key;
                $filter[$count]["table"] = $this->TABLE_NAME;

                unset($search_array[$key]);
                $count += 1;

            }

        }

        foreach($search_array[":join"] as $value){

            if(is_array($value)){

                $result = $this->prepareJoin($value);

                if($result["as"]){
                    $join[] = ["as" => $result["as"], "table" => $result["table"], "on" => $result["on"]];;
                }else{
                    $join[] = ["table" => $result["table"], "on" => $result["on"]];
                }

                $filter = array_merge($filter, $result["filter"]);

                if(isset($result["condition"]) && is_array($result["condition"])){
                    $condition = array_merge($condition, $result["condition"]);
                }

            }

        }

        if(isset($other["order_by"])){

            if(isset($other["order_by"]["att"]) && isset($this->MAINS[$other["order_by"]["att"]])){

                if(isset($other["order_by"]["det"]) && $other["order_by"]["det"] == "1" || $other["order_by"]["det"] == "0"){

                    $extra["order_by"]["att"] = $other["order_by"]["att"];
                    $extra["order_by"]["det"] = $other["order_by"]["det"];

                }

            }

        }

        if(isset($other["limit"]["start"]) && isset($other["limit"]["length"])){
            $extra["limit"]["start"] = intval($other["limit"]["start"]);
            $extra["limit"]["length"] = intval($other["limit"]["length"]);
        }

        $con = new Database($this->DATABASE, $this->DATABASE_NAME);
        $query = new JointSelection($this->TABLE_NAME, $filter, $join, $condition, $extra);

        $result_data = $con->executeInReturn($query);

        $data = [];
        while($row = $result_data->fetch()){
            $data[] = $row;
        }

        return $data;

    }

    /**
     * @param array $join
     * @return array
     * @throws OperationFailed
     */
    private function prepareJoin(Array $join){

        $return = [];

        if($join[":table"]){

            $name = $join[":table"];
            $model_name = "Application\\Models\\".$name."Model";
            $count = 0;

            /** @var SqlModel $model */
            $model = new $model_name;

            if($join[":on"] && $join[":parent"]){

                if(isset($model->MAINS[$join[":on"]])){

                    if(isset($this->MAINS[$join[":parent"]])){
                        //print "hello";

                        $return["table"] = $name;
                        $return["as"] = $representation = $join[":as"] ? $join[":as"] : null;
                        $return["on"] = $this->TABLE_NAME.".".$join[":parent"]." = ".($representation ? $representation : $model->TABLE_NAME).".".$join[":on"];

                        foreach($model->MAINS as $key => $value){

                            if(isset($join[$key])) {

                                $return["filter"][$count]["name"] = $key;
                                $return["filter"][$count]["table"] = $representation ? $representation : $model->TABLE_NAME;

                                if(is_array($join[$key])){

                                    foreach ($join[$key] as $cond){

                                        if(is_array($cond) && isset($cond["value"]) && isset($cond["equ"]) && isset($cond["det"])){

                                            $return["condition"][] = [
                                                "name" => ($representation ? $representation : $model->TABLE_NAME).".".$key,
                                                "value" => $cond["value"],
                                                "equ" => $cond["equ"],
                                                "det" => $cond["det"]
                                            ];

                                        }

                                    }

                                }
                                else if($join[$key] && !is_array($join[$key])){
                                    $return["condition"][] = [
                                        "name" => ($representation ? $representation : $model->TABLE_NAME).".".$key,
                                        "value" => $join[$key],
                                        "equ" => "=",
                                        "det" => "and"
                                    ];
                                }

                                $count += 1;

                            }

                        }

                    }else{
                        throw new OperationFailed("Attribute ".$join[":parent"]." not found in $this->TABLE_NAME");
                    }

                }else{
                    throw new OperationFailed("Attribute ".$join[":on"]." not found in $model->TABLE_NAME");
                }

            }else{
                throw new OperationFailed(":on and :parent attributes must be set");
            }

        }else{
            throw new OperationFailed("Table name should be defined.");
        }

        return $return;

    }

    public function hasMany($model_name){

        try{

            /** @var Model $model */
            $model = new $model_name;

            $reference = $model->getEntity()->getReference($this->TABLE_NAME);

            $value = $this->MAINS[$this->getEntity()->PRIMARY_KEY];

            if($value != ""){

                return $model->searchRecord(
                    [
                        [
                            "name" => "$reference",
                            "value" => $value,
                            "equ" => "=",
                            "det" => "and"
                        ]
                    ]
                );

            }
            else{

                FaultHandler::reportError(
                    "Empty Value!",
                    "The value of the attribute in the model ".$model->TABLE_NAME." is empty",
                    __FILE__." on Line ".__LINE__,
                    "immediate"
                );

                return [];

            }

        } catch (ClassNotFound $e) {
            $e->report();
            return [];
        }

    }

    /**
     * @param $attribute
     * @return int|mixed
     */
    function getMaxOf($attribute){

        $return = -1;
        $temp = [];

        if(isset($this->MAINS[$attribute])){

            $result = $this->searchRecord([], [$attribute]);

            foreach ($result as $val){

                $temp[] = $val[$attribute];

            }

            $return = max($temp);

        }

        return $return;

    }

    /**
     * @param $attribute
     * @return int|mixed
     */
    function getMinOf($attribute){

        $return = -1;
        $temp = [];

        if(isset($this->MAINS[$attribute])){

            $result = $this->searchRecord([], [$attribute]);

            foreach ($result as $val){

                $temp[] = $val[$attribute];

            }

            $return = min($temp);

        }

        return $return;

    }

    public function updateColumn(){
        // TODO: Implement updateColumn() method.
    }

    public function deleteColumn(){
        // TODO: Implement deleteColumn() method.
    }

    public function addColumn(){
        // TODO: Implement addColumn() method.
    }

}
