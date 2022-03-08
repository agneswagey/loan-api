<?php

use Respect\Validation\Validator as v;
use Respect\Validation\Factory;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails'   => true,
    ]
]);

$container = $app->getContainer();

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    return $view;
};

$container['validator'] = function ($container) {
    return new \App\Validation\Validator($container);
};

$container['UserController'] = function ($container) {
    return new \App\Controllers\UserController($container);
};

$container['AuthController'] = function ($container) {
    return new \App\Controllers\Auth\AuthController($container);
};

$container['RegisterController'] = function ($container) {
    return new \App\Controllers\RegisterController($container);
};

// f::withRuleNamespace('App\\Validation\\Rules', true);
Factory::setDefaultInstance(
    (new Factory())->withRuleNamespace('App\\Validation\\Rules')
);

require __DIR__.'/../app/routes.php';
