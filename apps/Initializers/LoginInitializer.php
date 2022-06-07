<?php
namespace Application\Initializers;

use Absoft\Line\Modeling\Initializer;

class LoginInitializer extends Initializer{

    /*
    public $VALUES = [
        [
            "id" => "the_id",
            "name" => "the_name",
        ],
        [
            "id" => "the_id",
            "name" => "the_name"
        ]
    ];

    */

    public $BUILDER = "Login";

    /*************************************************************************
        In this property you are expected to put all the values you want
        to insert into database. the you can initialize the operation from
        line cli.
    *************************************************************************/

    public $VALUES = [
        [
            "username" => "admin",
            "name" => "Abnet",
            "password" => "1234",
            "usertype" => "admin"
        ],
        [
            "username" => "user",
            "name" => "Brihanu",
            "password" => "1234",
            "usertype" => "user"
        ]
    ];

}
?>
