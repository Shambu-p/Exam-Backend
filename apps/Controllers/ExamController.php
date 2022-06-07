<?php
namespace Application\Controllers;

use Absoft\App\Security\AuthorizationManagement;
use Absoft\Line\FaultHandling\Exceptions\OperationFailed;
use Absoft\Line\HTTP\Request;
use Absoft\Line\Modeling\Controller;
use Absoft\Line\HTTP\Route;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;
use Application\Models\ExamModel;
use Application\Models\ExamQuestionsModel;
use Application\Models\QuestionModel;

class ExamController extends Controller{


    /**
     * @param $name
     * @param $parameter
     * @return Request|mixed|void
     * @throws OperationFailed
     * @throws RouteNotFound
     */
    public function route($name, $parameter){

        switch ($name) {
            case "show":
                $response = $this->show();
                break;
            case "save":
                $response = $this->save($parameter);
                break;
            case "take":
                $response = $this->take($parameter);
                break;
            case "add_question":
                $response = $this->addQuestion($parameter);
                break;
            case "update_count":
                $response = $this->changeCount($parameter);
                break;
            case "view":
                $response = $this->view($parameter);
                break;
            case "detail":
                $response = $this->detail($parameter);
                break;
            default:
                throw new RouteNotFound("ExamController.$name");
        }
        
        return $response;

    }

    private function show(){
        $model = new ExamModel();
        return $this->respond($model->searchRecord([]), 1);
    }
    
    private function view($request){
        $model = new ExamModel();
        return $this->respond($model->examQuestions($request->exam_id),1);
    }

    private function detail($request){
        $model = new ExamModel();
        return $this->respond($model->findRecord($request->exam_id),1);
    }

    private function save($request){

        $model = new ExamModel();
        return $this->respond($model->addExam($request->title, $request->subject, $request->description), 1);

    }
    
    private function delete($request){
        //TODO: here write deleting codes to be Executed
        return "";
    }

    private function take($parameter){

    }

    /**
     * @param $request
     * @return Request
     * @throws OperationFailed
     */
    private function addQuestion($request){

        $auth = AuthorizationManagement::getAuth($request->token, "user_auth");

        $model = new ExamQuestionsModel();
        return $this->respond($model->addQuestion($request->exam_id, $request->text, $auth["id"], $request->subject), 1);

    }

    private function changeCount($parameter){

    }

}
?>