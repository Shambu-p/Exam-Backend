<?php


namespace Absoft\Line\FaultHandling\Exceptions;


use Absoft\Line\FaultHandling\FaultHandler;

class DefaultRouteNotFound extends \Exception
{

    private $title = "RouteNotFound Exception";
    protected $file = "SystemConstructor/App/Routing/route.php";

    private $description = "
    The default route were not found. <br>
    try to insert the following code to the rout configuration file <br>
    <i><b>Route::setDefault('controller_name', 'method_name');</b></i> <br> 
    make sure there is no duplicate of this method in the route configuration file.";

    private $urgency = "immediate";


    function __construct($code = 0, $previous = null)
    {
        parent::__construct($this->description, $code, $previous);
        $this->report();

    }

    function report(){

        FaultHandler::reportError($this->title, $this->description, $this->file, $this->urgency);

    }

}
