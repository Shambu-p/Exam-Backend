<?php


namespace Absoft\Line\FaultHandling\Errors;


use Absoft\Line\FaultHandling\FaultHandler;

class DataOutOfRangeError {

    private $title = "Data Out Of Range Error";
    private $file = "Model file";
    private $description;
    private $urgency = "immediate";


    function __construct($message){

        $this->description = $message;

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}