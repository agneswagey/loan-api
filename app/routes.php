<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/register', function() {
    return 'Register here';
});

// $app->post('/register', 'RegisterController:register')->setName('register');
$app->post('/register', 'BodyParser:getInput')->setName('register');

$app->get("/customer", function (Request $request, Response $response){
    $sql = "SELECT * FROM customer";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});
