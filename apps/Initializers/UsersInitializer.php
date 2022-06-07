<?php
namespace Application\Initializers;

use Absoft\Line\Modeling\Initializer;

class UsersInitializer extends Initializer{

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
    
    public $BUILDER = "Users";

    /*************************************************************************
        In this property you are expected to put all the values you want
        to insert into database. the you can initialize the operation from
        line cli.
    *************************************************************************/

    public $VALUES = [
        [
            "fullname" => "Abnet Kebede",
            "role" => "admin",
            "grade" => "1",
            "age" => "10",
            "email" => "abnet@absoft.net",
            "password" => "password"
        ]
        // [
        //     "username" => "@abnet",
        //     "fullname" => "Abnet Kebede",
        //     "role" => "operator",
        //     "status" => "active",
        //     "email" => "abnet@absoft.net",
        //     "password" => "password"
        // ]
    ];
    
}
?>