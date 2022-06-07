<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 2/27/2021
 * Time: 6:01 PM
 */
namespace Absoft\Line\HTTP\Decode;

use Absoft\Line\FaultHandling\Errors\ForbiddenRoute;
use Absoft\Line\FaultHandling\Exceptions\DefaultRouteNotFound;
use Absoft\Line\FaultHandling\Exceptions\MissingParameter;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;
use Absoft\Line\HTTP\DataTransfer;
use Absoft\Line\HTTP\Request;
use Absoft\Line\HTTP\Route;
use Application\conf\Configuration;

class RequestAnalyzer
{

    /**
     * @var Request
     * this field will hold the request to be constructed.
     */
    private $request;

    /**
     * RequestAnalyzer constructor.
     * this method will initiate the request analysis by taking input from engine or
     * by initiating address analyzer
     */
    function __construct(){

        $this->request = new Request();
        try {
            $this->addressAnalyzer();
        } catch (MissingParameter $e) {
            $e->report();
        } catch (RouteNotFound $e) {
            $e->report();
        } catch (DefaultRouteNotFound $e) {
            $e->report();
        }

    }

    /**
     * @param $req_array
     * this method takes request URI as string and then check if the request is
     * view request by using isView method
     * and construct this request object.
     * @throws MissingParameter
     * @throws RouteNotFound
     */
    private function pagesAddress($req_array){

        $this->request->header->page_name = $req_array[2];
        $this->request->header->sub_page = $req_array[3];

        $route_name = "/pages/".$req_array[2]."/".$req_array[3];
        $this->request->link = Request::hostAddress().$route_name;

        $this->request->request = $this->pageRequest($route_name);

    }

    /**
     * @param $route_name
     * @return object
     * @throws MissingParameter
     * @throws RouteNotFound
     */
    private function pageRequest($route_name){

        $parameters = Route::get($route_name);
        $return = [];

        if($parameters){

            $def = explode("/", $parameters);
            $transfer = DataTransfer::get($route_name);

            if(sizeof($def) > 1){

                for($i = 1; $i < sizeof($def); $i++){

                    if(isset($_REQUEST[$def[$i]])){

                        $return[$def[$i]] = $_REQUEST[$def[$i]];

                    }
                    else if(isset($transfer->$def[$i])){

                        $return[$def[$i]] = (array) $transfer->$def[$i];

                    }
                    else{

                        throw new MissingParameter($this->request->link, $def[$i], __FILE__, __LINE__);

                    }

                }

            }

        }

        return (object) $return;

    }

    /**
     * @param $req array
     * @param $route_name
     * @return \stdClass
     * @throws MissingParameter
     * @throws RouteNotFound
     */
    private function getArguments($req, $route_name){

        $parameters = Route::get($route_name);
        $return = [];

        if($parameters){

            $def = explode("/", $parameters);
            $index = 0;

            if(sizeof($def) > 1){

                for($i = 1; $i < sizeof($def); $i++){

                    if(isset($_REQUEST[$def[$i]]) && !empty($_REQUEST[$def[$i]])){
                        $return[$def[$i]] = $_REQUEST[$def[$i]];
                    }
                    else if(isset($req[$index]) && !empty($req[$index])){
                        $return[$def[$i]] = $req[$index];
                    }
                    else{
                        throw new MissingParameter($this->request->link, $def[$i], __FILE__, __LINE__);
                    }

                    $index += 1;

                }

            }

        }
        else{

            for($i = 0; $i < sizeof($req); $i++){

                $return[] = $req[$i];

            }

        }

        return (object) $return;

    }

    private function getFiles(){

        if(sizeof($_FILES) > 0){

            $this->request->file = json_decode(json_encode($_FILES));

        }

    }

    /**
     * @param $req_array
     * @return integer
     * this method takes request URI as string and then check if the request is
     * view request
     */
    private function identifier($req_array){

        $size = sizeof($req_array);

        if($size > 1){

            if($req_array[1] == "pages" && $size >= 4){

                if(Configuration::$conf["type"] == "API"){
                    new ForbiddenRoute();
                }else{
                    return 2;
                }

            }
            else if($size >= 3){

                return 3;

            }

        }

        return 1;

    }

    /**
     * @param $req_array
     * this method takes request URI as string and then check if the request is
     * invoking user or system controller
     * @throws MissingParameter
     * @throws RouteNotFound
     */
    private function controllerAddress($req_array){

        $this->request->header->controller = $req_array[1]."Controller";
        $this->request->header->mtd = $req_array[2];

        $this->request->link = Request::hostAddress()."/".$req_array[1]."/".$req_array[2];

        $this->request->request = $this->getArguments(array_slice($req_array, 3), "/".$req_array[1]."/".$req_array[2]);

    }

    /**
     * @throws DefaultRouteNotFound
     */
    private function defaultController(){

        $default = Route::getDefault();

        $this->request->header->controller = $default["controller"];
        $this->request->header->mtd = $default["method"];

        $this->request->link = Request::hostAddress()."/".$default["controller"]."/".$default["method"];

    }

    /**
     * return
     * this method read the request URI and then construct the request
     * by dividing the request to header, requests and request files.
     * @throws MissingParameter
     * @throws RouteNotFound
     * @throws DefaultRouteNotFound
     */
    private function addressAnalyzer(){

        $req_array = explode("/", $_SERVER["REQUEST_URI"]);

        $id = $this->identifier($req_array);



        switch ($id) {
            case 2:
                $this->pagesAddress($req_array);
                $this->request->type = "view";
                break;
            case 3:
                $this->request->type = "control";
                $this->controllerAddress($req_array);
                break;
            default:
                $this->request->type = "control";
                $this->defaultController();
                break;
        }

        $this->getFiles();

    }

    public function getRequest(){
        return $this->request;
    }

}
