<?php 

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;

class Application extends App {

    public Request $request;

    /**
     * @return \Slim\Http\Request
     */
    function __construct(array $userSettings = array()) {

        parent::__construct($userSettings);

        $this->request = $this->post('/register', 'RegisterController:register')->setName('register');

        
    }

    /**
     * @return \Slim\Http\Response
     */
    public function invoke() {

        $this->middleware[0]->call();
        $this->response()->finalize();
        return $this->response();
        
    }
}