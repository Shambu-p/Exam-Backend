<?php
namespace Application\Models;

use Absoft\Line\FaultHandling\Exceptions\OperationFailed;
use Absoft\Line\Modeling\Models\Model;
use PDOStatement;

class ExamResultModel extends Model{

    /*
    public $MAINS = [
        "id" => "",
        "username" => "",
        "f_name" => ""
    ];

    */
    
    //As the name indicate this is the Table name of the Model
    
    public $TABLE_NAME = "ExamResult";

    /**********************************************************************
        In this property you are expected to put all the columns you want
        other than the fields you want to be hashed.
    ***********************************************************************/

    public $MAINS = [
        //@att_start
        "id" => "",
        "exam" => "",
        "user" => "",
        "score" => "",
        "date" => ""
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

    /**
     * @param $user
     * @return array|bool|PDOStatement
     * @throws OperationFailed
     */
    function byUser($user){

        return $this->advancedSearch([
            "id" => "",
            "exam" => "",
            "user" => $user,
            "score" => "",
            "date" => "",
            ":join" => [
                [
                    ":table" => "Exam",
                    ":on" => "id",
                    ":parent" => "id",
                    ":as" => "exam",

                    "id" => "",
                    "title" => "",
                    "date" => "",
                    "subject" => "",
                    "description" => "",
                    "count" => ""
                ]
            ]
        ]);

    }

    function examQuestions($result_id){

        $result = $this->findRecord($result_id);

        if(!sizeof($result)){
            return [];
        }

        $question_model = new ExamResultQuestionModel();
        $exam_model = new ExamModel();

        $questions = $question_model->byResult($result_id);
        $exam = $exam_model->findRecord($result["exam"]);

        return [
            "result" => $result,
            "exam" => $exam,
            "questions" => $questions
        ];

    }

    function createResult($exam_id, $user){

        $time = strtotime("now");

        $this->addRecord([
            "exam" => $exam_id,
            "user" => $user,
            "score" => 0,
            "date" => $time
        ]);

        return [
            "id" => $this->lastInsertId(),
            "exam" => $exam_id,
            "user" => $user,
            "score" => 0,
            "date" => $time
        ];

    }

    function updateScore($score, $result_id){

        $result = $this->findRecord($result_id);

        if(!sizeof($result)){
            return false;
        }

        if(!$this->updateRecord(
            [
                "score" => $score + $result["score"]
            ],
            [
                [
                    "name" => "id",
                    "value" => $result_id,
                    "equ" => "=",
                    "det" => "and"
                ]
            ]
        )){
            return false;
        };

        return true;

    }

}
?>