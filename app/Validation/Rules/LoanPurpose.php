<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class LoanPurpose {

    public function validate($input) {
        $purposes = array('vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment');
        
        foreach($purposes as $p) {
            if(v::key($p)->validate($input)) {
                break;
            } 
            $rules = v::contains($p)->validate($input)->setName('Loan purpose');
            
        }
        
        $output = $rules;
        
        return $output;
    }

    public function isValid($input, $purposes) {
        $isValid = v::contains($input)->validate($purposes);

        return $isValid;

    }

    public function getLoanPurpose() {
        $purpose = array('vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment');

        return $purpose;
    }


}