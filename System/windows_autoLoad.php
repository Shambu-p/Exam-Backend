<?php

include_once __DIR__."\Absoft\App\Files\DirConfiguration.php";

spl_autoload_register("myAutoloader");

ini_set('display_errors', 0);
error_reporting(E_ALL);
register_shutdown_function('ErrorHandler');


function myAutoLoader($className){

    $extension = ".php";

    if(strpos($className, "Application\\Controllers\\") > -1){

        $className = str_replace('Application\\Controllers\\', "", $className);
        $fullPath = dirname(__DIR__).str_replace("/", "\\",\Absoft\App\Files\DirConfiguration::$dir["controllers"]."\\" . $className . $extension);
        $en_fpath = $fullPath;

    }
    else if(strpos($className, "Application\\Models\\") > -1){

        $className = str_replace('Application\\Models\\', "", $className);
        $fullPath = dirname(__DIR__).str_replace("/", "\\",\Absoft\App\Files\DirConfiguration::$dir["models"]."\\" . $className . $extension);
        $en_fpath = $fullPath;

    }
    else if(strpos($className, "PHPMailer\\PHPMailer\\") > -1){

        $className = str_replace('PHPMailer\\PHPMailer\\', "", $className);
        $fullPath = dirname(__DIR__)."\\System\\Absoft\\App\\Mailer\\src\\" . $className . $extension;
        $en_fpath = $fullPath;

    }
    else if(strpos($className, "Application\\Builders\\") > -1){

        $className = str_replace('Application\\Builders\\', "", $className);
        $fullPath = dirname(__DIR__).str_replace("/", "\\",\Absoft\App\Files\DirConfiguration::$dir["builders"]."\\" . $className . $extension);
        $en_fpath = $fullPath;

    }
    else if(strpos($className, "Application\\Initializers\\") > -1){

        $className = str_replace('Application\\Initializers\\', "", $className);
        $fullPath = dirname(__DIR__).str_replace("/", "\\",\Absoft\App\Files\DirConfiguration::$dir["initializers"]."\\" . $className . $extension);
        $en_fpath = $fullPath;

    }
    else if (strpos($className, "Application\\") > -1){

        $className = str_replace('Application\\', "", $className);
        $fullPath = dirname(__DIR__)."\\apps\\" . $className . $extension;
        $en_fpath = $fullPath;

    }
    else {

        $fullPath = $className . $extension;
        $en_fpath = dirname(__DIR__)."\\System\\".$fullPath;

    }

    if(file_exists($en_fpath)){
        include_once $fullPath;
    }

}

/***** error handling ******/

function ErrorHandler()
{

    // Let's get last error that was fatal.
    $error = error_get_last();

    // This is error-only handler for example purposes; no error means that
    // there were no error and shutdown was proper. Also ensure it will handle

    if (null === $error) {
        return;
    }


    $error_type = $error["type"];
    $error_file = $error["file"];
    $error_line = $error["line"];
    $error_message = '' . $error["message"];

    \Absoft\Line\FaultHandling\FaultHandler::reportError($error_type, $error_message, ($error_file . " on line " . $error_line), "immediate");

}

include $_main_location . "/apps/conf/route.php";
