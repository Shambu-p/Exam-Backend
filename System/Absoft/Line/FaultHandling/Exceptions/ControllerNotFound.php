<?php

namespace Absoft\Line\FaultHandling\Exceptions;


use Absoft\Line\FaultHandling\FaultHandler;
use Throwable;

class ControllerNotFound extends \Exception
{

    private $title = "ControllerNotFound Exception";
    protected $file;
    private $description;
    private $urgency = "immediate";


    function __construct($controller_name, $file, $line, $code = 0, Throwable $previous = null){

        $this->description = "
        There is no Controller named ' $controller_name '. 
        this might be because the controller not defined in routes.php 
        or may be it is defined incorrectly.";

        $this->file = $file." on line ".$line;

        parent::__construct($this->description, $code, $previous);
        $this->report();

    }

    function report(){

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}
