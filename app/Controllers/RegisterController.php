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

class RegisterController extends Controller {
    
    public function register($request, $response) {
        // Set rules for name
        $name = trim($request->getParam('name'));
        $nameObj = new FirstLastName();
        $nameRules = $nameObj->validate($name, $response);
        $user_data['name'] = $nameRules[1];

        // Set rules for date of birth
        $dateOfBirth = $request->getParam('date_of_birth');
        $dobObj = new DateOfBirth();
        $dobRules = $dobObj->validate($dateOfBirth);
        if($dobRules) {
            $dobArr = explode("/", $dateOfBirth);
            $date = $dobArr[0];
            $month = $dobArr[1];
            $year = $dobArr[2];
            $dob = $date . $month . $year;
        }
        
        // Set rules for gender
        $gender = $request->getParam('gender');
        $genderObj = new Gender();
        $genderRules = $genderObj->validate($gender, $date, $month, $year);
        $dobNew = $genderRules[1];
        
        // Set rules for KTP
        $ktp = $request->getParam('ktp');
        $ktpObj = new KTP();
        $ktpRules = $ktpObj->validate($ktp, $dobNew);
        
        // Set rules for Loan Amount
        $loanAmount = $request->getParam('loan_amount');
        $loanAmountObj = new LoanAmount();
        $loanAmountRules = $loanAmountObj->validate($loanAmount);
        
        // Set rules for Loan period
        $loanPeriod = $request->getParam('loan_period');
        $loanPeriodObj = new LoanPeriod();
        $loanPeriodRules = $loanPeriodObj->validate($loanPeriod);

        // Set rules for Loan Purpose
        $loanPurpose = $request->getParam('loan_purpose');
        $loanPurposeObj = new LoanPurpose();
        $loanPurposeRules = $loanPurposeObj->validate($loanPurpose);
        

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
}