<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class Gender {

    public function validate($input, $date, $month, $year) {
        
        $rules = v::notEmpty();
        if(v::notEmpty()->equals('F')->validate($input)) {
            $dt = substr($date, 0, 1);
            if($dt == "0") {
                $dt1 = (int) str_replace("0", "", $date);
            }
            $dt_new = $dt1 + 40;
            
            $dob_new = $dt_new . $month . $year;

        } else if(v::notEmpty()->equals('M')->validate($input)) { 
            $dob_new = $date . $month . $year;
        }    

        $output = array($rules, $dob_new);
        
        return $output;
    }

}