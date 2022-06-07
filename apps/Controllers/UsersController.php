<?php
namespace Application\Controllers;

use Absoft\Line\FaultHandling\Exceptions\OperationFailed;
use Absoft\Line\HTTP\Request;
use Absoft\Line\Modeling\Controller;
use Absoft\Line\HTTP\Route;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;
use Application\Models\UsersModel;

class UsersController extends Controller{


    /**
     * @param $name
     * @param $parameter
     * @return Request|mixed|string
     * @throws OperationFailed
     * @throws RouteNotFound
     */
    public function route($name, $parameter){

        switch ($name) {
            case "show":
                $response = $this->show();
                break;
            case "register":
                $response = $this->save($parameter);
                break;
            default:
                throw new RouteNotFound("UsersController.$name");
        }
        
        return $response;

    }

    private function show(){
        //TODO: here write showing codes to be Executed
        return "";
    }
    
    private function view($request){
        //TODO: here write viewing codes to be Executed
        return "";
    }

    /**
     * @param $request
     * @return Request
     * @throws OperationFailed
     */
    private function save($request){

        $model = new UsersModel();
        if($request->password != $request->confirm_password){
            throw new OperationFailed("password does not match");
        }

        return $this->respond($model->register($request->name, $request->email, $request->age, $request->grade, $request->password), 1);

    }
    
    public function update($request){
        //TODO: here write updating codes to be Executed
        return "";
    }
    
    private function delete($request){
        //TODO: here write deleting codes to be Executed
        return "";
    }

}
?>