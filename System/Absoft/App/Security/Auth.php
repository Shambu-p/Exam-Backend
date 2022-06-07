<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 3/1/2021
 * Time: 9:40 AM
 */
namespace Absoft\App\Security;

use Absoft\Line\Modeling\Models\Model;
use Application\conf\AuthConfiguration;
use Application\Models\UsersModel;

class Auth {

    public static function grant($user){

        $user_model = new UsersModel();
        $_SESSION["auth"]["login"] = "true";

        foreach ($user_model->MAINS as $main => $value){

            if(isset($user[$main])){
                $_SESSION["auth"][$main] = $user[$main];
            }

        }

    }

    public static function user(){

        if(isset($_SESSION["auth"]) && sizeof($_SESSION["auth"]) > 0){
            return (object) $_SESSION["auth"];
        }

        return null;

    }

    public static function deni(){
        unset($_SESSION["auth"]);
    }

    public static function checkLogin(){

        if(isset($_SESSION["auth"]["login"]) && $_SESSION["auth"]["login"] == "true"){
            return true;
        }

        return false;

    }

    public static function checkUser($key, $value){

        if(self::checkLogin() && self::user()->$key == $value){

            return true;

        }

        return false;

    }

    public static function Authenticate($auth_name, array $parameters){

        $p_size = sizeof($parameters);
        $w_size = sizeof(AuthConfiguration::$conf[$auth_name]["with"]);

        if($p_size != $w_size && $p_size > 0 && $w_size > 0){
            return [];
        }

        $model_name = $auth_name == "admin_auth" ? "Absoft\\App\\Administration\\".AuthConfiguration::$conf[$auth_name]["table"]."Model" : "Application\\Models\\".AuthConfiguration::$conf[$auth_name]["table"]."Model";

        /** @var Model $model */
        $model = new $model_name;

        if(!isset($model->MAINS[AuthConfiguration::$conf[$auth_name]["with"][0]]) && !isset($model->HIDDEN[AuthConfiguration::$conf[$auth_name]["with"][0]])){
            return [];
        }

        if(isset(AuthConfiguration::$conf[$auth_name]["with"][1]) && !isset($model->MAINS[AuthConfiguration::$conf[$auth_name]["with"][1]]) && !isset($model->MAINS[AuthConfiguration::$conf[$auth_name]["with"][0]])){
            return [];
        }

        if(sizeof(AuthConfiguration::$conf[$auth_name]["with"]) && AuthConfiguration::$conf[$auth_name]["order"] == "keep"){

            $result = $model->searchRecord(
                [
                    [
                        "name" => AuthConfiguration::$conf[$auth_name]["with"][0],
                        "value" => $parameters[0],
                        "equ" => "=",
                        "det" => "and"
                    ]
                ]
            );

            if(isset(AuthConfiguration::$conf[$auth_name]["with"][1])){

                if(isset($model->HIDDEN[AuthConfiguration::$conf[$auth_name]["with"][1]])){

                    foreach($result as $res){

                        if(password_verify($parameters[1], $res[AuthConfiguration::$conf[$auth_name]["with"][1]])){
                            //$token = AuthorizationManagement::set($res, "user_auth");
                            return $res;
                        }

                    }

                }
                else if(isset($model->MAINS[AuthConfiguration::$conf[$auth_name]["with"][1]])){
                    foreach($result as $res){

                        if(password_verify($parameters[1], $res[AuthConfiguration::$conf[$auth_name]["with"][1]])){
                            //$token = AuthorizationManagement::set($res, "user_auth");
                            return $res;
                        }

                    }
                }

            }

        }
        else if(sizeof(AuthConfiguration::$conf[$auth_name]["with"]) && isset(AuthConfiguration::$conf[$auth_name]["order"]) && AuthConfiguration::$conf[$auth_name]["order"] == "once"){

            $condition = [];
            $count = 0;

            foreach(AuthConfiguration::$conf[$auth_name]["with"] as $with){

                $condition[] = [
                    "name" => $with,
                    "value" => $parameters[$count],
                    "equ" => "=",
                    "det" => "and"
                ];

                $count += 1;

            }

            $res = $model->searchRecord($condition);
            //$token = AuthorizationManagement::set($res, "user_auth");
            return $res;

        }

        return [];

    }

}
