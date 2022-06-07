<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 6/19/2020
 * Time: 11:00 PM
 */

namespace Absoft\Line\FaultHandling\Errors;


use Absoft\Line\FaultHandling\FaultHandler;

class EmptyArrayError{

    private $title = "Empty Array Passed!";
    private $file = "Model file";
    private $description;
    private $urgency = "immediate";


    function __construct($message){

        $this->description = $message;

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}