<?php
namespace Application\Controllers;

use Absoft\App\Security\Auth;
use Absoft\App\Security\AuthorizationManagement;
use Absoft\Line\FaultHandling\Exceptions\ForbiddenAccess;
use Absoft\Line\FaultHandling\FaultHandler;
use Absoft\Line\HTTP\Request;
use Absoft\Line\Modeling\Controller;
use Absoft\Line\FaultHandling\Exceptions\RouteNotFound;

class AuthController extends Controller{


    /**
     * @param $name
     * @param $parameter
     * @return mixed|string
     * @throws ForbiddenAccess
     * @throws RouteNotFound
     */
    public function route($name, $parameter){

        switch ($name){
            case "login":
                $response = $this->index($parameter);
                break;
            case "logout":
                $response = $this->logout($parameter);
                break;
            case "view":
                $response = $this->view($parameter);
                break;
            case "login_update":
                $response = $this->updateLogin($parameter);
                break;
            case "authorization":
                $response = $this->authorization($parameter);
                break;
            default:
                throw new RouteNotFound("AuthController.$name");
        }
        
        return $response;

    }

    /**
     * @param $request
     * @return string
     * @throws ForbiddenAccess
     */
    public function index($request){

        $auth = Auth::Authenticate("user_auth", [$request->email, $request->password]);

        if(sizeof($auth)){
            $token = AuthorizationManagement::set($auth, "user_auth");
            $cre = AuthorizationManagement::getAuth($token, "user_auth");
            $cre["token"] = $token;
            return $this->respond($cre, 1);
        }

        throw new ForbiddenAccess();

    }

    /**
     * @param $request
     * @return Request
     * @throws ForbiddenAccess
     */
    public function view($request){

        $saved = AuthorizationManagement::viewAuth($request->token, "user_auth");
        if(sizeof($saved) == 0){
            throw new ForbiddenAccess();
        }

        return $this->respond($saved, 1);

    }

    /**
     * @param $request
     * @return Request
     * @throws ForbiddenAccess
     */
    private function authorization($request){

//        if(!AuthorizationManagement::checkAuthorization($request->token, "admin_auth")){
//            throw new ForbiddenAccess();
//        }

        $saved = AuthorizationManagement::getAuth($request->token, "user_auth");

        if(sizeof($saved) == 0){
            throw new ForbiddenAccess();
        }

        return $this->respond($saved, 1);

    }

    /**
     * @param $request
     * @return Request
     * @throws ForbiddenAccess
     */
    private function updateLogin($request){

        $auths = AuthorizationManagement::viewAuth($request->token, "user_auth");
        $auth = Auth::Authenticate("user_auth", [$auths["username"], md5($request->password)]);

        if(!sizeof($auth)){
            throw new ForbiddenAccess();
        }

        $saved = AuthorizationManagement::update($request->token, "user_auth");
        if(sizeof($saved) == 0){
            throw new ForbiddenAccess();
        }

        return $this->respond($saved, 1);

    }

    public function logout($request){

        if(AuthorizationManagement::delete($request->token, "user_auth")){
            return $this->respond([], 1);
        }

        FaultHandler::reportError("Logout Failure", "logout is Failed.", __File__, "immediate");

        return "";
    }

}
?>
