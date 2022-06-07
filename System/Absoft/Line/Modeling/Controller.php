<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 10/26/2019
 * Time: 8:52 AM
 */

namespace Absoft\Line\Modeling;

use Absoft\App\API\Response;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;
use Absoft\Line\HTTP\Request;

abstract class Controller{

    public $_main_address;

    /** @var Request */
    public $request;
    public static $J = 1;
    public static $H = 2;

    public function __construct($request, $_main_address){

        $this->request = $request;
        $this->_main_address = $_main_address;

    }

    /**
     * @param $name
     * @param $parameter
     * @throws RouteNotFound
     * @return mixed
     */
    abstract public function route($name, $parameter);

    /**
     * @param $response
     * @param int $flag
     * @return Request
     */
    public function respond($response, $flag = 2){

        if($flag == 1){

            $resp = new Response($response);
            return $resp->respond();

        }else{

            header("Provider: Absoft");
            header("Access-Control-Allow-Origin: *");

            $return = new Request();
            $return->link = Request::hostAddress().$_SERVER["REQUEST_URI"];
            print $response;
            return $return;
        }

    }

    /**
     * @param $filename
     * @param bool $download
     * @return Request
     */
    public function respondFile($filename, $download = false){

        if($download){

            if(!file_exists($filename)){
                return Response::fileDownload($filename);
            }

            $return = new Request();
            $return->link = Request::hostAddress().$_SERVER["REQUEST_URI"];
            return $return;

        }else{

            if(!file_exists($filename)){
                return Response::fileContent($filename);
            }

            $return = new Request();
            $return->link = Request::hostAddress().$_SERVER["REQUEST_URI"];
            return $return;

        }

    }

    public function fileResponse($name, $size, $extension, $content, $download = false){

        if($download){
            return Response::fileDownload([
                "content" => $content,
                "name" => $name,
                "size" => $size,
                "extension" => $extension
            ]);
        }

        return Response::fileContent([
            "content" => $content,
            "name" => $name,
            "size" => $size,
            "extension" => $extension
        ]);

    }

}
