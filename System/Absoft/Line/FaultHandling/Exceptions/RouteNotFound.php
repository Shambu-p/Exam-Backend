<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 6/19/2020
 * Time: 11:00 PM
 */

namespace Absoft\Line\FaultHandling\Exceptions;


use Absoft\Line\FaultHandling\FaultHandler;
use Throwable;

class RouteNotFound extends \Exception
{

    private $title = "RouteNotFound Exception";
    protected $file = "SystemConstructor/App/Routing/route.php";
    private $description = "";
    private $urgency = "immediate";


    function __construct($route_name = "unknown", $code = 0, $previous = null)
    {

        $this->description = "there is no route named $route_name ";
        parent::__construct($this->description, $code, $previous);
        $this->report();

    }

    function report(){

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

    /*
    public static function reportError($title, $description, $error_file, $urgency="immediate"){

        $title = "RouteNotFound Exception";

        $file_address = explode(" on line ", $error_file);

        $file_lines = file($file_address[0]);

        $description .= "<br> ".implode("<br>", $file_lines);

        parent::reportError($title, $description, $error_file, $urgency);

    }*/

}
