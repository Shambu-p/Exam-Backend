<?php

namespace Absoft\Line\FaultHandling\Errors;

use Absoft\Line\FaultHandling\FaultHandler;

class DBConnectionError
{

    private $title = "Database Connection Failed!";
    private $file = "Database connection file";
    private $description;
    private $urgency = "immediate";


    function __construct($srv, $host, $db_name, $message){

        $this->description = "tried to connect $srv Database server <br> location: $host<br> to database named: $db_name <br>".$message;

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}