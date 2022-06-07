<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 7/15/2021
 * Time: 12:11 AM
 */

namespace Absoft\Line\Engines\CLI\Receivers;


use Absoft\App\Administration\Administration;
use Absoft\App\Administration\Builders;
use Absoft\App\Administration\Controllers;
use Absoft\App\Administration\Initializers;
use Absoft\App\Administration\Models;
use Absoft\Line\FaultHandling\Exceptions\ControllersFolderNotFound;
use Absoft\Line\FaultHandling\Exceptions\FileNotFound;
use Absoft\Line\FaultHandling\Exceptions\ModelsFolderNotFound;
use Absoft\Line\FaultHandling\Exceptions\OperationFailed;

class FileRemover
{

    public function remove($argument){

        $type = $argument["type"];
        $name = $argument["name"];

        if($type == "-c" || $type == "-controller" || $type == "controller"){
            $this->removeController($name);
            Administration::changeVariable($name, "controller", false);
        }
        else if($type == "-b" || $type == "-builder" || $type == "builder"){
            $this->removeBuilder($name);
            Administration::changeVariable($name, "builder", false);
        }
        else if($type == "-m" || $type == "-model" || $type == "model"){
            $this->removeModel($name);
            Administration::changeVariable($name, "model", false);
        }
        else if($type == "-i" || $type == "-initializer" || $type == "initializer"){
            $this->removeInitializer($name);
            Administration::changeVariable($name, "initializer", false);
        }
        else{
            print "incorrect or unknown type of generation encountered";
        }

    }

    /**
     * @param $name
     */
    private function removeController($name){

        $controller = new Controllers();

        try{

            if($controller->delete($name)){
                print "Controller removed \n";
            }else{
                print "Run into problem";
            }

        } catch (FileNotFound $e) {
            print $e->getMessage();
        }

    }

    private function removeInitializer($name){

        $initial = new Initializers();

        try{

            $initial->delete($name);
            print "Initializer removed \n";

        } catch (OperationFailed $e) {
            print $e->getMessage();
        } catch (FileNotFound $e) {
            print $e->getMessage();
        }

    }

    private function removeModel($name){

        $model = new Models();

        try {
            if ($model->delete($name)) {
                print "Model removed \n";
            } else {
                print "Run into problem";
            }
        } catch (FileNotFound $e) {
            print $e->getMessage();
        }

    }

    private function removeBuilder($name){

        $builder = new Builders();

        try{

            if($builder->delete($name)){
                print "Builder generated Successfully. \n";
            }else{
                print "System run into problem";
            }

        } catch (FileNotFound $e) {
            print $e->getMessage();
        }

    }

}
