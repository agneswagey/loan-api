<?php

namespace App\Validation\Exceptions;

use  Respect\Validation\Exceptions\ValidationException;

class FirstLastNameException extends ValidationException {

    public static $defaultTemplates = [

        self::MODE_DEFAULT => [
            self::STANDARD => 'Your name must be consist of two or more characters',
        ]

    ];

}