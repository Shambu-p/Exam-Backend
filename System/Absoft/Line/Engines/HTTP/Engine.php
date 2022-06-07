<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 2/27/2021
 * Time: 5:59 PM
 */

namespace Absoft\Line\Engines\HTTP;

use Absoft\Line\FaultHandling\Exceptions\ControllerNotFound;
use Absoft\App\Files\DirConfiguration;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;
use Absoft\Line\HTTP\Decode\RequestAnalyzer;
use Absoft\Line\HTTP\Request;

class Engine
{

    /**
     * @var Request
     * this field will hold the request sent to the server.
     */
    private $request;
    private $main_folder;

    /**
     * Engine constructor.
     * @param $location string
     */
    function __construct($location){

        $this->main_folder = $location;
        $decoder = new RequestAnalyzer();

        $this->request = $decoder->getRequest();
        DirConfiguration::$_main_folder = $location;

    }

    /**
     * @return void
     */
    function start(){

        if($this->request->isView()){

            //print "it is view";
            $view = new ViewerEngine($this->request, $this->main_folder);
            //print "good view";
            $view->start();

        }
        else if($this->request->isControl()){

            try {

                $control = new ControlEngine($this->request, $this->main_folder);
                $response = $control->start();

                $this->interpretResponse($response);

            } catch (ControllerNotFound $e) {
                $e->report();
            } catch (RouteNotFound $e) {
                $e->report();
            } catch (\Exception $ex){
                die();
            }

        }

    }

    private function interpretResponse(Request $request){

        $this->request = $request;
        $this->start();

    }

}
