<?php
namespace Application\Models;

use Absoft\Line\Modeling\Models\Model;

class ExamResultQuestionModel extends Model{

    /*
    public $MAINS = [
        "id" => "",
        "username" => "",
        "f_name" => ""
    ];

    */
    
    //As the name indicate this is the Table name of the Model
    
    public $TABLE_NAME = "ExamResultQuestion";

    /**********************************************************************
        In this property you are expected to put all the columns you want
        other than the fields you want to be hashed.
    ***********************************************************************/

    public $MAINS = [
        //@att_start
        "exam_result" => "",
        "question" => "",
        "choice" => "",
        "state" => ""
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

    function byResult($exam_id){

        return $this->searchRecord([
            [
                "name" => "exam_result",
                "value" => $exam_id,
                "equ" => "=",
                "det" => "and"
            ]
        ]);

    }

    function addQuestion($question_id, $choice_id, $state, $exam_id){

        $this->addRecord([
            "exam_result" => $exam_id,
            "question" => $question_id,
            "choice" => $choice_id,
            "state" => $state
        ]);

        return [
            "id" => $this->lastInsertId(),
            "exam_result" => $exam_id,
            "question" => $question_id,
            "choice" => $choice_id,
            "state" => $state
        ];

    }

}
?>