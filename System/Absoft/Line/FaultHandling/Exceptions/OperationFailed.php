<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 5/28/2021
 * Time: 6:03 PM
 */

namespace Absoft\Line\FaultHandling\Exceptions;


use Absoft\Line\FaultHandling\FaultHandler;

class OperationFailed extends \Exception{

    private $title = "Operation Failed!";
    protected $file = "unknown file";
    private $description = "";
    private $urgency = "immediate";


    function __construct($message, $code = 0, $previous = null){

        $this->description = $message;
        parent::__construct($this->description, $code, $previous);
        //print $message;
        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}
