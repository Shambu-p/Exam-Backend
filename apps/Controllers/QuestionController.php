<?php
namespace Application\Controllers;

use Absoft\App\Security\Auth;
use Absoft\App\Security\AuthorizationManagement;
use Absoft\Line\Modeling\Controller;
use Absoft\Line\HTTP\Route;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;
use Application\Models\ChoicesModel;
use Application\Models\QuestionModel;

class QuestionController extends Controller{


    public function route($name, $parameter)
    {

        switch ($name) {
            case "view":
                $response = $this->view($parameter);
                break;
            case "save":
                $response = $this->save($parameter);
                break;
            case "add_choice":
                $response = $this->addChoice($parameter);
                break;
            case "update_answer":
                $response = $this->update($parameter);
                break;
            default:
                throw new RouteNotFound("QuestionController.$name");
        }
        
        return $response;

    }
    
    private function view($request){

        $model = new QuestionModel();
        $question = $model->getQuestion($request->question_id);

        return $this->respond($question, 1);

    }

    private function save($request){

        $model = new QuestionModel();
        $auth = AuthorizationManagement::getAuth($request->token, "user_auth");

        $model->addQuestion($request->text, $request->token, $request->subject);

        return $this->respond([
            "id" => $model->lastInsertId(),
            "text" => $request->text,
            "user" => $request->token,
        ], 1);

    }
    
    public function update($request){
        $model = new QuestionModel();
        $model->updateAnswer($request->answer, $request->question_id);

        return $this->respond([], 1);
    }
    
    private function delete($request){
        //TODO: here write deleting codes to be Executed
        return "";
    }

    private function addChoice($request){
        $model = new ChoicesModel();
        return $this->respond($model->addChoice($request->text, $request->question_id), 1);
    }

}
?>