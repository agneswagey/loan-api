<?php

namespace App\Controllers;

use \Slim\Views\Twig as View;
use \App\Validation\Rules\FirstLastName;
use \App\Validation\Rules\DateOfBirth;
use \App\Validation\Rules\Gender;
use \App\Validation\Rules\KTP;
use \App\Validation\Rules\LoanAmount;
use \App\Validation\Rules\LoanPeriod;
use \App\Validation\Rules\LoanPurpose;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException as e;
use Respect\Validation\Rules\AbstractRule;

class RegisterController extends Controller {
    
    public function register($request, $response) {
        $parsedBody = $request->getParsedBody();
        
        $name = trim($parsedBody['name']);
        $dateOfBirth = $parsedBody['dateOfBirth'];
        $gender = $parsedBody['gender'];
        $ktp = $parsedBody['ktp'];
        $loanAmount = $parsedBody['loanAmount'];
        $loanPeriod = $parsedBody['loanPeriod'];
        $loanPurpose = $parsedBody['loanPurpose'];
        
        // Set validation for name
        $nameRules = $this->nameValidator($name);
        $user_data['name'] = $nameRules[1];

        // Set validation for date of birth
        $dobRules = $this->dateOfBirthValidator($dateOfBirth);
        if($dobRules) {
            $dobArr = explode("/", $dateOfBirth);
            $date = $dobArr[0];
            $month = $dobArr[1];
            $year = $dobArr[2];
            $dob = $date . $month . $year;
        }
        
        // Set validation for gender
        $genderRules = $this->genderValidator($gender, $date, $month, $year);
        $dobNew = $genderRules[1];
        
        // Set validation for KTP
        $ktpRules = $this->ktpValidator($ktp, $dobNew);
        
        // Set validation for Loan Amount
        $loanAmountRules = $this->loanAmountValidator($loanAmount);
        
        // Set validation for Loan period
        $loanPeriodRules = $this->loanPeriodValidator($loanPeriod);

        // Set validation for Loan Purpose
        $loanPurposeRules = $this->loanPurposeValidator($loanPurpose);
        

        $rules = array(
            'name'  => $nameRules[0],
            'date_of_birth'  => $dobRules,
            'ktp'  => $ktpRules,
            'loan_amount'  => $loanAmountRules,
            'loan_period'  => $loanPeriodRules,
            'loan_purpose'  => $loanPurposeRules,
            'gender'  => $genderRules[0],
        );

        $data = array(
            'name'  => $nameRules[1],
            'date_of_birth'  => $dateOfBirth,
            'ktp'  => $ktp,
            'loan_amount'  => $loanAmount,
            'loan_period'  => $loanPeriod,
            'loan_purpose'  => $loanPurpose,
            'gender'  => $gender,
        );

        
        foreach($data as $key => $val) {
            try{
                $rules[$key]->check($val);
                $user_data[$key] = $val;
            } catch(\InvalidArgumentException $e) {
                $errors = $e->getMessage();
                // $response->getBody()->write(json_encode($errors));
                
                return $response->withJson($errors, 401);
                
            }
        }

        // Write data on a file
        $text = 'NEW DATA ' . PHP_EOL;
        foreach($user_data as $k => $v) {
            $text .= ucfirst(str_replace("_", " ", $k)) . ' : ' . $request->getParam($k) . PHP_EOL ;
        } 
        $text .= '===========================' . PHP_EOL;

        $file = fopen("file.txt","w");
        fwrite($file, $text);
        fclose($file);

        return $response->withJson('Success. Registered data file has been created', 200);
    }

    private function nameValidator($input) {
        $nameArr = count(explode(" ", $input));
        $rules = v::notEmpty()->min(2)->setName('name');
        $output = array($rules, $nameArr, $input);
        
        return $output;
    }

    private function dateOfBirthValidator($input) {
        return v::notEmpty()->date('d/m/y')->setName('Date of birth');
    }

    private function genderValidator($input, $date, $month, $year) {
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

    private function ktpValidator($input, $dobNew) {
        $ktp1Str = substr($input, 0, 6);
        $ktp2Str = substr($input, 12, 4);
        $ktpFull = $ktp1Str . $dobNew . $ktp2Str;
        
        return v::notEmpty()->numericVal()->equals($ktpFull)->length(16,16)->setName('KTP');
    }

    private function loanAmountValidator($input) {
        return v::notEmpty()->intVal()->between(1000, 10000)->setName('Loan amount');
    }

    private function loanPeriodValidator($input) {
        return v::notEmpty()->numericVal()->setName('Loan period');
    }

    private function loanPurposeValidator($input) {
        $purposes = array('vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment');

        return v::in($purposes)->setName("Loan Purpose");
    }
}