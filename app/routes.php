<?php

$app->get('/register', function() {
    return 'Register here';
});

$app->post('/register', 'RegisterController:register')->setName('register');

