<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 1/19/2020
 * Time: 10:30 AM
 */

namespace Absoft\Line\DbConnection\QueryConstruction\SQL;

use Absoft\Line\DbConnection\QueryConstruction\Query;

class Update implements Query {

    private $query = "";
    private $values = [];

    function __construct($table, $set, $condition = array()){

        /*


        $arra = [
            "column" => "value",
            "column2 => "avalu"
        ]

         */

        $this->query = "update $table set ";
        $sets = QueryConstructor::setDerdari($set, "sets");
        $this->query .= $sets["query"];
        $this->values = $sets["values"];

        if(sizeof($condition) > 0){

            $cnt = [];

            foreach($condition as $value){

                if(isset($cnt[$value["name"]])){

                    $cnt[$value["name"]] += 1;

                }else{
                    $cnt[$value["name"]] = 1;
                }

                //print "condition_".$ctn[$value["name"]].$value["name"]."\n";
                $this->values["condition_".$cnt[$value["name"]].$value["name"]] = $value["value"];

            }

            $this->query = $this->query. " where ";
            $this->query = $this->query. QueryConstructor::conditionDerdari($condition);

        }

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
