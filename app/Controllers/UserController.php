<?php

namespace App\Controllers;

use \Slim\Views\Twig as View;

class UserController extends Controller {
    
    public function index($request, $response) {
        return $this->view->render($response, 'templates/app.twig');
    }
}