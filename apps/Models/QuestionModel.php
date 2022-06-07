<?php
namespace Application\Models;

use Absoft\Line\Modeling\Models\Model;

class QuestionModel extends Model{

    /*
    public $MAINS = [
        "id" => "",
        "username" => "",
        "f_name" => ""
    ];

    */
    
    //As the name indicate this is the Table name of the Model
    
    public $TABLE_NAME = "Question";

    /**********************************************************************
        In this property you are expected to put all the columns you want
        other than the fields you want to be hashed.
    ***********************************************************************/

    public $MAINS = [
        //@att_start
        "id" => "",
        "text" => "",
        "subject" => "",
        "answer" => "",
        "user" => ""
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

    function getQuestion($question_id){

        $question = $this->findRecord($question_id);

        if(!sizeof($question)){
            return [];
        }

        $choice_model = new ChoicesModel();
        $choices = $choice_model->getByQuestion($question_id);

        return [
            "question" => $question,
            "choices" => $choices
        ];

    }

    function addQuestion($text, $user, $subject){

        $this->addRecord([
            "text" => $text,
            "subject" => $subject,
            "answer" => 0,
            "user" => $user
        ]);

    }

    function updateAnswer($answer, $question_id){

        $this->updateRecord([
            "answer" => $answer
        ],
        [
            [
                "name" => "id",
                "value" => $question_id,
                "equ" => "=",
                "det" => "and"
            ]
        ]);

    }

}
?>