<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class FirstLastName {

    public function validate($input, $response) {
        $nameArr = count(explode(" ", $input));
        $rules = v::notEmpty()->min(2)->setName('name');
        $output = array($rules, $nameArr, $input);
        
        return $output;
    }

    public function countNameWords($input) {
        $countName = count(explode(" ", $input));
        
        return $countName;
    }

}