<?php

namespace Application\Models;



class Pagination {

    public static function get($name, $page){
        
        if(!isset($_SESSION["pager"][$name])){
            return [];
        }

        if($page <= 0){
            $page = 1;
        }

        if(intval($page) > intval($_SESSION["pager"][$name]["max_page"])){
            $page = 1;
        }

        return [
            "start" => $page < intval($_SESSION["pager"][$page]["max_page"]) ? (intval($_SESSION["pager"][$page]["page_size"]) * (intval($page) - 1)) : $_SESSION["pager"][$page]["page_size"],
            "length" => $_SESSION["pager"][$page]["page_size"]
        ];

    }

    public static function setPage($name, $num_pages, $page_size){
        $_SESSION["pager"][$name] = [
            "page_size" => $page_size,
            "max_page" => $num_pages
        ];
    }

    public static function check($name){

        if(isset($_SESSION["pager"][$name])){
            return true;
        }

        return false;

    }

    public static function getPager($name){

        if(isset($_SESSION["pager"][$name])){
            return $_SESSION["pager"][$name];
        }

        return null;

    }

}