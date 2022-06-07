<?php
namespace Application\Models;

use Absoft\Line\Modeling\Models\Model;

class ExamModel extends Model{

    /*
    public $MAINS = [
        "id" => "",
        "username" => "",
        "f_name" => ""
    ];

    */
    
    //As the name indicate this is the Table name of the Model
    
    public $TABLE_NAME = "Exam";

    /**********************************************************************
        In this property you are expected to put all the columns you want
        other than the fields you want to be hashed.
    ***********************************************************************/

    public $MAINS = [
        //@att_start
        "id" => "",
        "title" => "",
        "date" => "",
        "subject" => "",
        "description" => "",
        "count" => ""
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


    function bySubject($subject){
        return $this->searchRecord([
            [
                "name" => "subject",
                "value" => $subject,
                "equ" => "=",
                "det" => "and"
            ]
        ]);
    }

    function examQuestions($exam_id){

        $exam = $this->findRecord($exam_id);

        if(!sizeof($exam)){
            return [];
        }

        $question_model = new ExamQuestionsModel();

        $questions = $question_model->byExam($exam_id);

        return [
            "exam" => $exam,
            "questions" => $questions
        ];

    }

    function addExam($title, $subject, $description){

        $date = strtotime("now");
        $this->addRecord([
            "title" => $title,
            "date" => $date,
            "subject" => $subject,
            "description" => $description,
            "count" => 0
        ]);

        return [
            "id" => $this->lastInsertId(),
            "title" => $title,
            "date" => $date,
            "subject" => $subject,
            "count" => 0
        ];

    }

    function updateCount($count, $exam_id){

        $exam = $this->findRecord($exam_id);

        if(!sizeof($exam)){
            return false;
        }

        if(!$this->updateRecord([
            "count" => $count + intval($exam["count"])
        ],
        [
            [
                "name" => "id",
                "value" => $exam_id,
                "equ" => "=",
                "det" => "and"
            ]
        ])){
            return false;
        };

        return true;

    }

}
?>