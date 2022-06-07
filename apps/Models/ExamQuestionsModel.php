<?php
namespace Application\Models;

use Absoft\Line\FaultHandling\Exceptions\OperationFailed;
use Absoft\Line\Modeling\Models\Model;

class ExamQuestionsModel extends Model{

    /*
    public $MAINS = [
        "id" => "",
        "username" => "",
        "f_name" => ""
    ];

    */
    
    //As the name indicate this is the Table name of the Model
    
    public $TABLE_NAME = "ExamQuestions";

    /**********************************************************************
        In this property you are expected to put all the columns you want
        other than the fields you want to be hashed.
    ***********************************************************************/

    public $MAINS = [
        //@att_start
        "exam" => "",
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


    function byExam($exam_id){

        return $this->searchRecord([
            [
                "name" => "exam",
                "value" => $exam_id,
                "equ" => "=",
                "det" => "and"
            ]
        ]);

    }

    /**
     * @param $exam
     * @param $text
     * @param $user
     * @param $subject
     * @return array
     * @throws OperationFailed
     */
    function addQuestion($exam, $text, $user, $subject){

        $model = new QuestionModel();
        $exam_model = new ExamModel();
        $model->beginTransaction();

        $model->addRecord([
            "text" => $text,
            "subject" => $subject,
            "answer" => 0,
            "user" => $user
        ]);

        $question_id = $model->lastInsertId();

        $this->beginTransaction();

        if(!$this->addRecord([
            "exam" => $exam,
            "question" => $question_id
        ])){
            $model->rollback();
            throw new OperationFailed("cannot add question to examination.");
        }

        if(!$exam_model->updateCount(1, $exam)){
            $model->rollback();
            $this->rollback();
            throw new OperationFailed("cannot add question to examination.");
        }

        $model->commit();
        $this->commit();

        return [
            "exam" => $exam,
            "question" => $question_id
        ];

    }

}
?>