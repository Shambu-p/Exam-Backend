<?php

namespace Absoft\Line\FaultHandling\Exceptions;


use Absoft\Line\FaultHandling\FaultHandler;

class MissingParameter extends \Exception
{

    private $title = "MissingParameter Exception";
    protected $file;
    private $description;
    private $urgency = "immediate";


    function __construct($link, $parameter, $file, $line, $code = 0, \Throwable $previous = null)
    {

        $this->description = "In route ".$link." parameter named ".$parameter." missed.";
        $this->file = $file." on line ".$file;

        parent::__construct($this->description, $code, $previous);
        $this->report();

    }

    function report(){

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}
