<?php
namespace Application\Controllers;

use Absoft\Line\Modeling\Controller;
use Absoft\Line\HTTP\Route;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;
use Application\Models\SubjectModel;

class SubjectsController extends Controller{


    public function route($name, $parameter){

        switch ($name) {
            case "show":
                $response = $this->show();
                break;
            default:
                throw new RouteNotFound("SubjectsController.$name");
        }
        
        return $response;

    }

    private function show(){
        $model = new SubjectModel();
        return $this->respond($model->searchRecord([]), 1);
    }

}
?>