<?php
namespace Application\Models;

use Absoft\Line\Modeling\Models\Model;

class UsersModel extends Model{

    /*
    public $MAINS = [
        "id" => "",
        "username" => "",
        "f_name" => ""
    ];

    */
    
    //As the name indicate this is the Table name of the Model
    
    public $TABLE_NAME = "Users";

    /**********************************************************************
        In this property you are expected to put all the columns you want
        other than the fields you want to be hashed.
    ***********************************************************************/

    public $MAINS = [
        //@att_start
        "id" => "",
        "fullname" => "",
        "email" => "",
        "age" => "",
        "grade" => "",
        "role" => ""
        //@att_end
    ];
    
    /**********************************************************************
        In this field you are expected to put all columns you want to be
        encrypted or hashed.
    ***********************************************************************/
    
    public $HIDDEN = [
        //@hide_start
        "password" => ""
        //@hide_end
    ];

    function register($name, $email, $age, $grade, $password){
        $this->addRecord([
            "fullname" => $name,
            "email" => $email,
            "age" => $age,
            "grade" => $grade,
            "password" => $password,
            "role" => "student"
        ]);

        return [
            "id" => $this->lastInsertId(),
            "fullname" => $name,
            "email" => $email,
            "age" => $age,
            "grade" => $grade,
            "password" => $password,
            "role" => "student"
        ];
    }

}
?>