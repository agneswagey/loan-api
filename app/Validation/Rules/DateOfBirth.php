<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class DateOfBirth {

    public function validate($input) {
        
        $rules = v::notEmpty()->date('d/m/y')->setName('Date of birth');
        
        return $rules;
    }


}