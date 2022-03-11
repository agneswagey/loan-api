<?php

namespace Api\Models;

use Respect\Validation\Validator as v;

class Validation {

    public function nameValidator($input) {
        $nameArr = count(explode(" ", $input));
        $rules = v::notEmpty()->min(2)->setName('name');
        $output = array($rules, $nameArr, $input);
        
        return $output;
    }

    public function dateOfBirthValidator($input) {
        return v::notEmpty()->date('d/m/y')->setName('Date of birth');
    }

    public function genderValidator($input, $date, $month, $year) {
        $rules = v::notEmpty();
        if(v::notEmpty()->equals('F')->validate($input)) {
            $dt = substr($date, 0, 1);
            if($dt == "0") {
                $dt1 = (int) str_replace("0", "", $date);
            }
            $dtNew = $dt1 + 40;
            
            $dobNew = $dtNew . $month . $year;

        } else if(v::notEmpty()->equals('M')->validate($input)) { 
            $dobNew = $date . $month . $year;
        }    

        $output = array($rules, $dobNew);
        
        return $output;
    }

    public function ktpValidator($input, $dobNew) {
        $ktp1Str = substr($input, 0, 6);
        $ktp2Str = substr($input, 12, 4);
        $ktpFull = $ktp1Str . $dobNew . $ktp2Str;
        
        return v::notEmpty()->numericVal()->equals($ktpFull)->length(16,16)->setName('KTP');
    }

    public function loanAmountValidator($input) {
        return v::notEmpty()->intVal()->between(1000, 10000)->setName('Loan amount');
    }

    public function loanPeriodValidator($input) {
        return v::notEmpty()->numericVal()->setName('Loan period');
    }

    public function loanPurposeValidator($input) {
        $purposes = array('vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment');

        return v::in($purposes)->setName("Loan Purpose");
    }

}