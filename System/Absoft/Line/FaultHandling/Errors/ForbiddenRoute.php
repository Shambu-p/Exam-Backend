<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 4/27/2021
 * Time: 12:21 AM
 */

namespace Absoft\Line\FaultHandling\Errors;


use Absoft\Line\FaultHandling\FaultHandler;

class ForbiddenRoute {

    private $title = "Forbidden Route!";
    private $file = "Model file";
    private $description = "The address that you are trying to access is forbidden!";
    private $urgency = "immediate";


    function __construct(){

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}
