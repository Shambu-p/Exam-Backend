<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 5/26/2021
 * Time: 9:57 AM
 */

namespace Absoft\Line\FaultHandling\Exceptions;


use Absoft\Line\FaultHandling\FaultHandler;

class ControllersFolderNotFound extends \Exception {

    private $title = "ControllersFolderNotFound Exception";
    protected $file;
    private $description;
    private $urgency = "immediate";


    function __construct($file_address, $file, $line, $code = 0, \Throwable $previous = null){

        $this->description = "
        There is no directory with address ' $file_address '. does not exist \n it is because the Controllers folder address is changed in DirConfiguration file.";

        $this->file = $file." on line ".$line;

        parent::__construct($this->description, $code, $previous);
        $this->report();

    }

    function report(){

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}
