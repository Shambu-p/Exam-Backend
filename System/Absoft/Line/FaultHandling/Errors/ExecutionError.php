<?php

namespace Absoft\Line\FaultHandling\Errors;


use Absoft\Line\FaultHandling\FaultHandler;

class ExecutionError
{

    private $title = "Query Execution Failed!";
    private $file = "Database connection file";
    private $description;
    private $urgency = "immediate";


    function __construct($message){

        $this->description = $message;

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}