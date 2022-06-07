<?php


namespace Absoft\Line\FaultHandling\Exceptions;


use Absoft\Line\FaultHandling\FaultHandler;

class ClassNotFound extends \Exception {

    private $title = "ClassNotFound Exception";
    protected $file;
    private $description;
    private $urgency = "immediate";


    function __construct($class_name, $file, $line, $code = 0, \Throwable $previous = null){

        $this->description = "
        There is no Class named ' $class_name '. 
        this might be because the class were not defined  
        or may be it is defined incorrectly.";

        $this->file = $file." on line ".$line;

        parent::__construct($this->description, $code, $previous);
        $this->report();

    }

    function report(){

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}
