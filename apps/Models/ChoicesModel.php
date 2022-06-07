<?php
namespace Application\Models;

use Absoft\Line\Modeling\Models\Model;

class ChoicesModel extends Model{

    /*
    public $MAINS = [
        "id" => "",
        "username" => "",
        "f_name" => ""
    ];

    */
    
    //As the name indicate this is the Table name of the Model
    
    public $TABLE_NAME = "Choices";

    /**********************************************************************
        In this property you are expected to put all the columns you want
        other than the fields you want to be hashed.
    ***********************************************************************/

    public $MAINS = [
        //@att_start
        "id" => "",
        "text" => "",
        "question" => ""
        //@att_end
    ];
    
    /**********************************************************************
        In this field you are expected to put all columns you want to be
        encrypted or hashed.
    ***********************************************************************/
    
    public $HIDDEN = [
        //@hide_start
        //@hide_end
    ];


    function getByQuestion($question){

        return $this->searchRecord([
            [
                "name" => "question",
                "value" => $question,
                "equ" => "=",
                "det" => "and"
            ]
        ]);

    }

    function addChoice($text, $question_id){

        $this->addRecord([
            "text" => $text,
            "question" => $question_id
        ]);

        return [
            "id" => $this->lastInsertId(),
            "text" => $text,
            "question" => $question_id
        ];

    }

}
?>