<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 1/19/2020
 * Time: 11:12 AM
 */

namespace Absoft\Line\DbConnection\QueryConstruction\SQL;

use Absoft\Line\DbConnection\QueryConstruction\Query;

class Deletion implements Query {

    public $query = "";
    public $values = array();

    function deleteRecord($table, $condition){

        $this->query = "delete from $table";

        if(sizeof($condition) > 0){

            $this->query .= " where ";
            $this->query = $this->query.QueryConstructor::conditionDerdari($condition);

            $cnt = [];

            foreach($condition as $value){

                if(isset($cnt[$value["name"]])){

                    $cnt[$value["name"]] += 1;

                }else{
                    $cnt[$value["name"]] = 1;
                }

                $name = "condition_".$cnt[$value["name"]].$value["name"];
                $this->values[$name] = $value['value'];

            }

            /*foreach($condition as $value){

                $this->values["condition_".$value["name"]] = $value["value"];

            }*/

        }

    }

    function dropTable($table_name){

        $this->query = "drop table $table_name";
        $this->values = array();

    }

    function dropColumn(){

    }

    function getQuery()
    {
        return $this->query;
    }

    function getValues()
    {
        return $this->values;
    }
}
