<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 1/18/2020
 * Time: 9:44 PM
 */

namespace Absoft\Line\DbConnection\QueryConstruction\SQL;

use Absoft\Line\DbConnection\QueryConstruction\Query;
use Absoft\Line\Modeling\DbBuilders\Builder;

class Selection implements Query {

    private $values = array();
    private $query = "";

    /**
     * @param Builder $tables
     * @param array $filter
     * @param array $condition
     *      $tables should be object of an Entity
     *      $filter should be array of columns which are supposed to be shown in the result
     *      $condition should be associative array of [attribute name => ['value' => value, 'equ' => '=,>,<', 'det' => 'and,or']]
     * @param Builder|null $join_table
     * @param array $order_by
     */
    function __construct(Builder $tables, $filter = ["*"], $condition = [], Builder $join_table = null, $other = []){

        $this->select($filter);

        if($join_table != null){

            $this->from($tables, $join_table);
            $this->join($tables, $join_table);

        }
        else{
            $this->from($tables);
        }

        $this->where($condition);

        if(isset($other["order_by"]) && isset($other["order_by"]["att"])){
            $this->orderBy($other["order_by"]);
        }

        if(isset($other["limit"])){
            $this->limit($other["limit"]);
        }

    }

    function select($filter){

        //select column1, column2, column3 from table_name, table2, table3 where column = value
        $this->query = "select ";


        if(sizeof($filter) == 0){

            $this->query = $this->query . "* ";

        }else if(sizeof($filter) > 0){

            $this->query = $this->query.QueryConstructor::singleDerdari($filter);

        }

    }

    function from($table, $join=null){

        //from table_name, table1

        $this->query = $this->query." from ". $table->TABLE_NAME;

        if($join != null){

            $this->query = $this->query.", ". $join->TABLE_NAME;

        }

    }

    function where($condition){

        if(sizeof($condition) > 0){

            $count = 0;
            $this->query = $this->query." where ";
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

        }

    }

    function join(Builder $table, Builder $join){

        if($join != null){

            $this->query .= " join ".$join->TABLE_NAME;

            if($here = $table->getReference($join->TABLE_NAME)){

                $this->query .= " on $table.$here = ".$join->TABLE_NAME.".$join->PRIMARY_KEY ";

            }else if($here = $join->getReference($table->TABLE_NAME)){

                $this->query .= " on $table.$table->PRIMARY_KEY = ".$join->TABLE_NAME.".$here ";

            }

        }

    }

    function orderBy($order_by){

        /*

        $array = [
            "att" => "column",
            "det" => 1
        ]

         */

        $this->query .= " order by ".$order_by["att"];

        if($order_by["det"] == "1"){
            $this->query .= " asc";
        }else{
            $this->query .= " desc";
        }

    }

    function limit($limit){
        $this->query .= " limit ".$limit["start"].", ".$limit["length"];
    }

    function getQuery(){
        return $this->query;
    }

    function getValues(){
        return $this->values;
    }
}
