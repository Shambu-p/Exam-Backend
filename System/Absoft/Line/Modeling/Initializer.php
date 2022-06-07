<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 11/5/2020
 * Time: 6:20 PM
 */
namespace Absoft\Line\Modeling;



use Absoft\Line\DbConnection\Database;
use Absoft\Line\DbConnection\QueryConstruction\SQL\Insertion;
use Absoft\Line\FaultHandling\Errors\DataOutOfRangeError;
use Absoft\Line\FaultHandling\Exceptions\ClassNotFound;
use Absoft\Line\Modeling\Models\Model;

abstract class Initializer
{

    public $VALUES;
    public $MODEL;

    /**
     * @param $base_name
     * @return bool
     * @throws ClassNotFound
     */
    public function initialize($base_name){

        /*
        $return = [];
        $in_values = [];
        $size = sizeof($this->VALUES);
         */

        $model_name = 'Application\\Models\\'.$base_name.'Model';

        /** @var Model $model */
        if($model = new $model_name){

            return $model->addMultiple($this->VALUES);

            /*


            if($model != null){

                $mains_size = sizeof($model->MAINS);
                $hidden_size = sizeof($model->HIDDEN);

                if($size > 0){

                    $con = new Database($model->DATABASE, $model->DATABASE_NAME);

                    foreach($this->VALUES as $values){

                        $new_size = sizeof($values);

                        if($new_size > 0 && $new_size <= ($mains_size + $hidden_size)){

                            foreach($model->MAINS as $key => $value){

                                if(isset($values[$key]) && $values[$key] != ""){

                                    $in_values[$key] = $values[$key];

                                }

                            }

                            foreach($model->HIDDEN as $key => $value){

                                if(isset($values[$key]) && $values[$key] != ""){

                                    $in_values[$key] = password_hash($values[$key], PASSWORD_DEFAULT);

                                }

                            }

                            $result = $con->execute(new Insertion($model->TABLE_NAME, $in_values));

                            if($result["message"] == "1"){

                                $return[] = "inserted success fully";

                            }

                            $return[] = $result["returned"];

                        }else{

                            $return[] = "data out of range";
                            new DataOutOfRangeError("There is no value to be inserted. the array is empty.");

                        }

                    }

                }
                else{

                    $return[] = "No Data to be Inserted";

                }

            }-
             */

        }else{
            throw new ClassNotFound($model_name, "initializer abstract file", __LINE__);
        }

    }

}
