<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 5/26/2021
 * Time: 8:46 AM
 */

namespace Absoft\App\Administration;


use Absoft\App\Files\DirConfiguration;
use Absoft\App\Files\Resource;
use Absoft\Line\FaultHandling\Exceptions\ControllersFolderNotFound;
use Absoft\Line\FaultHandling\Exceptions\FileNotFound;
use Absoft\Line\FaultHandling\Exceptions\OperationFailed;

class Controllers
{

    private $content = '<?php
namespace Application\Controllers;

use Absoft\Line\Modeling\Controller;
use Absoft\Line\HTTP\Route;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;

class @_nameController extends Controller{


    public function route($name, $parameter)
    {

        switch ($name) {
            case "show":
                $response = $this->show();
                break;
            case "save":
                $response = $this->save($parameter);
                break;
            default:
                throw new RouteNotFound("@_nameController.$name");
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

    private function save($request){
        //TODO: Here write save codes to be Executed
        return "";
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
?>';

    /**
     * @param $name
     * @return bool
     * @throws ControllersFolderNotFound
     * @throws OperationFailed
     *
     * this method will generate controller file with default codes.
     */
    public function create($name){

        $content = str_replace("@_name", $name, $this->content);

        if(!Resource::checkFile(DirConfiguration::$dir["controllers"]."/".$name."Controller.php")){

            if(file_put_contents(DirConfiguration::$_main_folder.DirConfiguration::$dir["controllers"]."/".$name."Controller.php", $content)){
                return true;
            }else{
                return false;
            }

        }else{
            throw new OperationFailed("file with the same name exist".DirConfiguration::$dir["controllers"]."/".$name."Controller.php");
        }

    }

    public function generateCRUDOperations(){

    }

    /**
     * @param $name
     * @return bool
     * @throws FileNotFound
     * this method will delete/remove user/developer defined controller
     */
    public function delete($name){

        if(Resource::checkFile(DirConfiguration::$dir["controllers"]."/".$name."Controller.php")){

            if(unlink(DirConfiguration::$_main_folder.DirConfiguration::$dir["controllers"]."/".$name."Controller.php")){
                return true;
            }

        }else{
            throw new FileNotFound(DirConfiguration::$_main_folder.DirConfiguration::$dir["controllers"]."/".$name."Controller.php", __File__, __Line__);
        }

        return false;

    }

    /**
     * @return array
     * @throws ControllersFolderNotFound
     * this method will provide list of user/developer defined controllers.
     */
    public function all(){

        $return = [];

        if(Resource::checkFile(DirConfiguration::$dir["controllers"])){

            if(is_dir(DirConfiguration::$_main_folder.DirConfiguration::$dir["controllers"])){

                $list = dir(DirConfiguration::$_main_folder.DirConfiguration::$dir["controllers"]);

                while(($file = $list->read()) != false) {

                    if ($file == "." || $file == "..") {

                        continue;

                    } else {

                        if(strpos($file, "Controller.php") > 0){

                            $return[] = substr($file, 0, strpos($file, ".php"));

                        }

                    }

                }

            }
            else{

                throw new ControllersFolderNotFound(DirConfiguration::$dir["controllers"], __FILE__, __LINE__);

            }

        }
        else{

            throw new ControllersFolderNotFound(DirConfiguration::$dir["controllers"], __FILE__, __LINE__);

        }

        return $return;

    }

}
