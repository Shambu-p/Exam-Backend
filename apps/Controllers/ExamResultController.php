<?php
namespace Application\Controllers;

use Absoft\App\Security\AuthorizationManagement;
use Absoft\Line\FaultHandling\Exceptions\ForbiddenAccess;
use Absoft\Line\FaultHandling\Exceptions\OperationFailed;
use Absoft\Line\HTTP\Request;
use Absoft\Line\Modeling\Controller;
use Absoft\Line\HTTP\Route;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;
use Application\Models\ExamResultModel;
use Application\Models\ExamResultQuestionModel;
use Application\Models\QuestionModel;

class ExamResultController extends Controller{


    /**
     * @param $name
     * @param $parameter
     * @return Request|mixed|string
     * @throws ForbiddenAccess
     * @throws OperationFailed
     * @throws RouteNotFound
     */
    public function route($name, $parameter){

        switch ($name) {
            case "show":
                $response = $this->show($parameter);
                break;
            case "save":
                $response = $this->save($parameter);
                break;
            case "view":
                $response = $this->view($parameter);
                break;
            case "add_result":
                $response = $this->addResult($parameter);
                break;
            case "update_score":
                $response = $this->update($parameter);
                break;
            default:
                throw new RouteNotFound("ExamResultController.$name");
        }
        
        return $response;

    }

    /**
     * @param $request
     * @return Request
     * @throws ForbiddenAccess
     * @throws OperationFailed
     */
    private function show($request){

        if(!AuthorizationManagement::checkAuthorization($request->token, "user_auth")){
            throw new ForbiddenAccess();
        }

        $saved = AuthorizationManagement::getAuth($request->token, "user_auth");

        $model = new ExamResultModel();
        return $this->respond($model->byUser($saved["id"]), 1);

    }

    /**
     * @param $request
     * @return Request
     * @throws ForbiddenAccess
     */
    private function view($request){

        if(!AuthorizationManagement::checkAuthorization($request->token, "user_auth")){
            throw new ForbiddenAccess();
        }

        $model = new ExamResultModel();
        return $this->respond($model->examQuestions($request->result_id), 1);
    }

    /**
     * @param $request
     * @return Request
     * @throws ForbiddenAccess
     */
    private function save($request){

        if(!AuthorizationManagement::checkAuthorization($request->token, "user_auth")){
            throw new ForbiddenAccess();
        }

        $saved = AuthorizationManagement::getAuth($request->token, "user_auth");

        $model = new ExamResultModel();
        return $this->respond($model->createResult($request->exam_id, $saved["id"]), 1);

    }
    
    public function update($request){
        //TODO: here write updating codes to be Executed
        return "";
    }
    
    private function delete($request){
        //TODO: here write deleting codes to be Executed
        return "";
    }

    /**
     * @param $request
     * @return Request
     * @throws ForbiddenAccess
     */
    private function addResult($request){

        if(!AuthorizationManagement::checkAuthorization($request->token, "user_auth")){
            throw new ForbiddenAccess();
        }

        $model = new ExamResultQuestionModel();
        $result_model = new ExamResultModel();
        $question_model = new QuestionModel();

        $question = $question_model->findRecord($request->question);

        if(isset($question["answer"])){
            $state = ($question["answer"] == $request->choice) ? "true" : "false";
        }else{
            $state = "false";
        }
        $model->beginTransaction();
        $result = $model->addQuestion($request->question, $request->choice, $state, $request->result_id);

        if($state == "true"){
            if($result_model->updateScore(1, $request->result_id)){
                $model->commit();
            }else{
                $model->rollback();
            }
        }else{
            $model->commit();
        }

        return $this->respond($result, 1);

    }

}
?>