<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException as e;


class AuthController extends Controller {
    protected $user_data = array();

    public function getRegister($request, $response) {
        return $this->view->render($response, 'register.twig');
    }

    public function postRegister($request, $response) {
        // Set rules for name
        $name = trim($request->getParam('name'));
        $name_arr = count(explode(" ", $name));
        $name_rules = v::notEmpty()->min(2)->setName('name');

        // Set rules for date of birth
        $date_of_birth = $request->getParam('date_of_birth');
        $date_of_birth_rules = v::notEmpty()->date('d/m/y')->setName('Date of birth');
        if($date_of_birth_rules->validate($date_of_birth)) {
            $date_of_birth_arr = explode("/", $date_of_birth);
            $date = $date_of_birth_arr[0];
            $month = $date_of_birth_arr[1];
            $year = $date_of_birth_arr[2];
            $dob = $date . $month . $year;
        }
        $dob_new = '';
        
        // Set rules for gender
        $gender = $request->getParam('gender');
        $gender_rules = v::notEmpty();
        if(v::notEmpty()->equals('F')->validate($gender)) {
            $dt = substr($date, 0, 1);
            if($dt == "0") {
                $dt1 = (int) str_replace("0", "", $date);
            }
            $dt_new = $dt1 + 40;
            
            $dob_new = $dt_new . $month . $year;

        } else if(v::notEmpty()->equals('M')->validate($gender)) { 
            $dob_new = $dob;
        }    
        
        // Set rules for KTP
        $ktp = $request->getParam('ktp');
        $ktp1_str = substr($ktp, 0, 6);
        $ktp2_str = substr($ktp, 12, 4);
        $ktp_full = $ktp1_str . $dob_new . $ktp2_str;
        $ktp_rules = v::notEmpty()->numericVal()->equals($ktp_full)->length(16,16)->setName('KTP');
        
        // Set rules for Loan Amount
        $loan_amount = $request->getParam('loan_amount');
        $loan_amount_rules = v::notEmpty()->intVal()->between(1000, 10000)->setName('Loan amount');

        // Set rules for Loan period
        $loan_period = $request->getParam('loan_period');
        $loan_period_rules = v::notEmpty()->numericVal()->setName('Loan period');
        
        // Set rules for Loan Purpose
        $loan_purpose = $request->getParam('loan_purpose');
        $purpose = array('vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment');
        $loan_purpose_arr = explode(" ", $loan_purpose);
        foreach($loan_purpose_arr as $loan_p) {
            if(in_array(strtolower($loan_p), $purpose)) {
                $isValid = true;
                break;
            }
        }


        $rules = array(
            'name'  => $name_rules,
            'date_of_birth'  => $date_of_birth_rules,
            'ktp'  => $ktp_rules,
            'loan_amount'  => $loan_amount_rules,
            'loan_period'  => $loan_period_rules,
            'gender'  => $gender_rules,
        );

        $data = array(
            'name'  => $name_arr,
            'date_of_birth'  => $date_of_birth,
            'ktp'  => $ktp,
            'loan_amount'  => $loan_amount,
            'loan_period'  => $loan_period,
            'gender'  => $gender,
        );

        
        foreach($data as $key => $val) {
            try{
                $rules[$key]->check($val);
                $user_data[$key] = $val;
            } catch(\InvalidArgumentException $e) {
                $errors = $e->getMessage();
                // $response->getBody()->write(json_encode($errors));
                
                return $response->withJson($errors);
                
            }
        }
        
        // Write data on a file
        $text = 'NEW DATA ' . PHP_EOL;
        foreach($user_data as $k => $v) {
            $text .= ucfirst(str_replace("_", " ", $k)) . ' : ' . $request->getParam($k) . PHP_EOL ;
        } 
        $text .= '===========================' . PHP_EOL;

        $file = fopen("file.txt","a+");
        fwrite($file, $text);
        fclose($file);
       

        // if (!$isValid) {
        //     $errors = 'ga valid';
        //     $response->getBody()->write(json_encode($errors));
        //     return $response->withHeader('content-type', 'application/json')->withStatus(500);
        // }
        

        return $response->withRedirect($this->router->pathFor('register'));
    }

    

    /**
     * Set the user subscription constraints
     *
     * @return void
     */
    public function initRules()
    {   return 'sini'; die();
        $dateFormat = 'd-m-Y';
        $now = (new \DateTime())->format($dateFormat);
        $tenYears = (new \DateTime('+10 years'))->format($dateFormat);

        $this->rules['username'] = V::alnum('_')->noWhitespace()->length(4, 20)->setName('Username');
        $this->rules['password'] = V::alnum()->noWhitespace()->length(4, 20)->setName('password');
        $this->rules['email'] = V::email();
        $this->rules['cardHolderName'] = V::alpha()->setName('Card holder name');
        $this->rules['cardNumber'] = V::creditCard()->setName('card number');
        $this->rules['billingAddress'] = V::string()->length(6)->setName('billing address');
        $this->rules['cvc'] = V::numeric()->length(3, 4)->setName('CVC');
        $this->rules['expirationDate'] = V::date($dateFormat)->between($now, $tenYears)->setName('expiration date');
    }

    public function initMessages()
    {
        $this->messages = [
            'alpha'                 => '{{name}} must only contain alphabetic characters.',
            'alnum'                 => '{{name}} must only contain alpha numeric characters and dashes.',
            'numeric'               => '{{name}} must only contain numeric characters.',
            'noWhitespace'          => '{{name}} must not contain white spaces.',
            'length'                => '{{name}} must length between {{minValue}} and {{maxValue}}.',
            'email'                 => 'Please make sure you typed a correct email address.',
            'creditCard'            => 'Please make sure you typed a valid card number.',
            'date'                  => 'Make sure you typed a valid date for the {{name}} ({{format}}).',
            'password_confirmation' => 'Password confirmation doesn\'t match.'
        ];
    }

    public function assert(array $inputs)
    {
        $expirationMonth = getParsedBodyParam($inputs, 'expirationMonth');
        // $expirationYear = array_get($inputs, 'expirationYear');
        // $inputs['expirationDate'] = '01-' . $expirationMonth . '-' . $expirationYear;

        // foreach ($this->rules as $rule => $validator) {
        //     try {
        //         $validator->assert(array_get($inputs, $rule));
        //     } catch (\Respect\Validation\Exceptions\NestedValidationExceptionInterface $ex) {
        //         $this->errors = $ex->findMessages($this->messages);
        //         return false;
        //     }
        // }

        // $passwordConfirmed = $this->assertPasswordConfirmation($inputs);

        // return $passwordConfirmed;
    }
}