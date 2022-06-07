<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 5/28/2021
 * Time: 6:03 PM
 */

namespace Absoft\Line\FaultHandling\Errors;


use Absoft\Line\FaultHandling\FaultHandler;

class OperationFailed {

    private $title = "Operation Failed!";
    protected $file = "unknown file";
    private $description;
    private $urgency = "immediate";


    function __constructor($message){

        $this->description = $message;
        //print $message;

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}
