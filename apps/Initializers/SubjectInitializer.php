<?php
namespace Application\Initializers;

use Absoft\Line\Modeling\Initializer;

class SubjectInitializer extends Initializer{

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
    
    public $BUILDER = "Subject";

    /*************************************************************************
        In this property you are expected to put all the values you want
        to insert into database. the you can initialize the operation from
        line cli.
    *************************************************************************/

    public $VALUES = [
        [
            "name" => "Mathematics"
        ],
        [
            "name" => "English"
        ],
        [
            "name" => "Science"
        ]
    ];
    
}
?>