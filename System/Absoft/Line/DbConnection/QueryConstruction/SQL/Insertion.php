<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 1/18/2020
 * Time: 10:19 PM
 */

namespace Absoft\Line\DbConnection\QueryConstruction\SQL;

use Absoft\Line\DbConnection\QueryConstruction\Query;

class Insertion implements Query {

    private $query = "";
    private $values = [];

    function __construct($table, $values = [], $multiple = false){

        if(!$multiple){
            $temp = $values;
            $values = [];
            $values[] = $temp;
        }

        /*
         print_r($values);
        print "\n";
         */

        $value_size = sizeof([$values]);

        if($value_size > 0 && $table != null){

            $this->query = "insert into $table ";
            $filter = [];
            $last = [];

            foreach($values as $k => $inserts){

                $temp = [];
                foreach($inserts as $key => $value){

                    if(!in_array($key, $filter)){
                        $filter[] = $key;
                    }

                    $name = "insertion_$k$key";
                    $temp[] = ":".$name;
                    $this->values[$name] = $value;
                }

                $last[] = " (".QueryConstructor::singleDerdari($temp).")";
                //print_r($inserts);
                //print "\n";

            }

            $this->query = $this->query."(".QueryConstructor::singleDerdari($filter).") values ";
            $this->query = $this->query.QueryConstructor::singleDerdari($last).";";

        }
    }

    function getQuery(){
        return $this->query;
    }

    function getValues(){
        return $this->values;
    }

}
