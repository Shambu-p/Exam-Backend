<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 6/19/2020
 * Time: 11:00 PM
 */
namespace Absoft\Line\FaultHandling;

use Absoft\App\API\Response;
use Absoft\Line\HTTP\Route;
use Application\conf\Configuration;

abstract class FaultHandler{

    /**
     * @return array
     */
    public static function getError(){

        return $_SESSION["_system"]["error_handling"]["error"];

    }

    public static function checkError(){

        if(isset($_SESSION["_system"]["error_handling"]["occurrence"]) && ($_SESSION["_system"]["error_handling"]["occurrence"] != null || $_SESSION["_system"]["error_handling"]["occurrence"] != "")){
            return $_SESSION["_system"]["error_handling"]["occurrence"];
        }else{
            return false;
        }

    }

    public static function showError(){

        if (self::checkError()) {

            if(Configuration::$conf["type"] == "API"){
                $res = new Response([]);
                $res->respond();
                die();
            }else if(Configuration::$conf["type"] == "UI"){
                Route::view("system", "error");
            }else{
                print $_SESSION["_system"]["error_handling"]["error"]["description"]."\n";
                self::clearError();
            }
            die();

        }

    }

    public static function displayError(){

        $_SESSION["_system"]["error_handling"]["occurrence"] = false;
        return Route::display("system", "error");

    }

    public static function reportError($title, $description, $error_file, $urgency = "not_immediate"){

        $_SESSION["_system"]["error_handling"]["error"]["title"] = $title;
        $_SESSION["_system"]["error_handling"]["error"]["description"] = $description;
        $_SESSION["_system"]["error_handling"]["error"]["error_file"] = $error_file;
        $_SESSION["_system"]["error_handling"]["occurrence"] = true;

        if($urgency == "immediate"){

            self::showError();

        }

        /*
        if(isset(Configuration::$conf["type"]) && Configuration::$conf["type"] != "API"){}
        */

    }

    public static function clearError(){

        $_SESSION["_system"]["error_handling"]["error"] = [];
        $_SESSION["_system"]["error_handling"]["occurrence"] = false;

    }

    /*
    public static function errorForApi(){

        $_SESSION["_system"]["error_handling"]["error_for"] = "API";

    }

    public static function errorForUI(){

        $_SESSION["_system"]["error_handling"]["error_for"] = "UI";

    }*/

}
