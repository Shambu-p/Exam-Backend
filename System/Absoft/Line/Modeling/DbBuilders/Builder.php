<?php


namespace Absoft\Line\Modeling\DbBuilders;



use Absoft\Line\DbConnection\Database;
use Absoft\Line\DbConnection\QueryConstruction\SQL\Creation;
use Absoft\Line\DbConnection\QueryConstruction\SQL\Drop;
use Absoft\Line\FaultHandling\Exceptions\ReferenceNotFound;

abstract class Builder {

    public $ATTRIBUTES;
    public $HIDDEN_ATTRIBUTES;
    public $TABLE_NAME;
    public $PRIMARY_KEY = "id";
    public $DATABASE = "MySql";
    public $DATABASE_NAME = "first";


    public function __construct(){

        $schema = new Schema();
        $this->construct($schema);

    }

    abstract function construct(Schema $schema);

    public function create(){

        $con = new Database($this->DATABASE, $this->DATABASE_NAME);
        return $con->execute(new Creation($this->TABLE_NAME, array_merge($this->ATTRIBUTES, $this->HIDDEN_ATTRIBUTES)));

    }

    public function drop(){

        $con = new Database($this->DATABASE, $this->DATABASE_NAME);
        return $con->execute(new Drop($this->TABLE_NAME));

    }

    public function checkAttribute($attribute_name){

    }

    /**
     * @param $table_name
     * @return mixed
     * @throws ReferenceNotFound
     */
    public function getReference($table_name){

        $reference = "";

        foreach($this->ATTRIBUTES as $attribute){

            if($attribute->foreign == $table_name){

                return $attribute->name;

            }

        }

        throw new ReferenceNotFound($this->TABLE_NAME, $table_name);

    }

    public function getPrimaryAttribute(){

        return $this->PRIMARY_KEY;

    }

}