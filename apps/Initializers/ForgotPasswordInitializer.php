<?php
namespace Application\Initializers;

use Absoft\Line\Modeling\Initializer;

class ForgotPasswordInitializer extends Initializer{

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
    
    public $BUILDER = "ForgotPassword";

    /*************************************************************************
        In this property you are expected to put all the values you want
        to insert into database. the you can initialize the operation from
        line cli.
    *************************************************************************/

    public $VALUES = [
        [
            "username" => "@admin",
            "question" => "What your childhood nike name",
            "answer" => "babbi"
        ],
        [
            "username" => "@admin",
            "question" => "What your childhood nike name",
            "answer" => "babbi"
        ]
    ];
    
}
?>