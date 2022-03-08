<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class LoanAmount {

    public function validate($input) {
        $rules = v::notEmpty()->intVal()->between(1000, 10000)->setName('Loan amount');
        
        $output = $rules;
        
        return $output;
    }

    public function isValid($input) {
        $isValid = v::notEmpty()->intVal()->between(1000, 10000)->validate($input);

        return $isValid;

    }

}