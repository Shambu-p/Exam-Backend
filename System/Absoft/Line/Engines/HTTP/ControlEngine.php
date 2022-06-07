<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 2/27/2021
 * Time: 11:45 PM
 */

namespace Absoft\Line\Engines\HTTP;

use Absoft\App\Administration\AdminController;
use Absoft\App\Files\DirConfiguration;
use Absoft\App\Files\Resource;
use Absoft\Line\FaultHandling\Exceptions\BuildersFolderNotFound;
use Absoft\Line\FaultHandling\Exceptions\ClassNotFound;
use Absoft\Line\FaultHandling\Exceptions\ControllerNotFound;
use Absoft\Line\FaultHandling\Exceptions\ControllersFolderNotFound;
use Absoft\Line\FaultHandling\Exceptions\FileNotFound;
use Absoft\Line\FaultHandling\Exceptions\ModelsFolderNotFound;
use Absoft\Line\FaultHandling\Exceptions\OperationFailed;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;
use Absoft\Line\HTTP\Request;
use Absoft\Line\Modeling\Controller;

class ControlEngine
{

    private $request;
    private $_main_address;

    function __construct(Request $request, $_main_folder){

        $this->request = $request;
        $this->_main_address = $_main_folder;

    }

    private function configuredControllerLoader(){

    }

    /**
     * @return Controller
     * @throws ControllerNotFound
     */
    private function defaultControllerLoader(){

        $full_c_name = 'Application\\Controllers\\'.$this->request->header->controller;

        if(Resource::checkFile(DirConfiguration::$dir["controllers"]."/".$this->request->header->controller.".php")){
            return new $full_c_name($this->request, $this->_main_address);
        }else{
            throw new ControllerNotFound($this->request->header->controller, __FILE__, __LINE__);
        }

    }

    private function configurationCheck(){

    }

    /**
     * @return Controller
     * @throws ControllerNotFound
     */
    private function adminController(){
        //$full_c_name = 'Absoft\\App\\Administration\\AdminController';

        if(Resource::checkFile("/System/Absoft/App/Administration/AdminController.php")){
            return new AdminController($this->request, $this->_main_address);
        }else{
            throw new ControllerNotFound($this->request->header->controller, __FILE__, __LINE__);
        }
    }

    /**
     * @return Request
     * @throws ControllerNotFound
     * @throws RouteNotFound
     * @throws BuildersFolderNotFound
     * @throws ClassNotFound
     * @throws ControllersFolderNotFound
     * @throws FileNotFound
     * @throws ModelsFolderNotFound
     * @throws OperationFailed
     */
    public function start(){

        /** @var $controller_object Controller **/
        if($this->request->header->controller == "AdminController"){
            $controller_object = $this->adminController();
        }else{
            $controller_object = $this->defaultControllerLoader();
        }

        return $controller_object->route($this->request->header->mtd, $this->request->request);

    }

}
