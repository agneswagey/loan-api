<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class KTP {

    public function validate($input, $dobNew) {
        $ktp1Str = substr($input, 0, 6);
        $ktp2Str = substr($input, 12, 4);
        $ktpFull = $ktp1Str . $dobNew . $ktp2Str;
        $rules = v::notEmpty()->numericVal()->equals($ktpFull)->length(16,16)->setName('KTP');
        
        $output = $rules;
        
        return $output;
    }

}