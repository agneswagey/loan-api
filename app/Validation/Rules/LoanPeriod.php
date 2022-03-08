<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class LoanPeriod {

    public function validate($input) {
        $rules = v::notEmpty()->numericVal()->setName('Loan period');
        
        $output = $rules;
        
        return $output;
    }

}