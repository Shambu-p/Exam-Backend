<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 2/27/2021
 * Time: 8:47 PM
 */
namespace Absoft\Line\HTTP\Encode;

use Absoft\Line\HTTP\Request;

abstract class RequestConstructor {

    public $request;


    /**
     * RequestConstructor constructor.
     * @param $route_name
     * @param $request
     * @param array $files
     */
    function __construct($route_name, $request, $files = []){

        $this->request = new Request();
        $this->mainConstruction($route_name, $request, $files);

    }

    /**
     * @param $route_name string
     * this method accept route name as string
     * for page routing the parent and sub folder name will be
     * written with dot in between.
     * here the header of the request will be produced
     * @return \stdClass
     * it will return header as std class
     */
    abstract function headerConstruction(string $route_name);

    /**
     * @param \stdClass $header
     * @param array $request_array
     * this method accepts requests as associative array
     * here the request body will be constructed for the request
     */
    abstract function requestConstruction(\stdClass $header, array $request_array);

    /**
     * @param $array array
     * this method accept file request as associative array and
     * @return \stdClass
     * returns file request as stdClass
     * here the file request part will be created
     */
    function fileConstruction(array $array){

        return (object) $array;

    }

    /**
     * @param string $route_name
     * @param array $request
     * @param array $files
     * @return mixed
     *
     * here is where the request construction organized by using the other methods
     */
    abstract function mainConstruction(string $route_name, array $request, array $files);

    /**
     * @return Request
     */
    function getRequest(){
        return $this->request;
    }

}
