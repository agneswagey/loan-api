<?php

namespace App\Controllers;

use \Slim\Views\Twig as View;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException as e;
use Respect\Validation\Rules\AbstractRule;
use Api\Models\Customer;
use Api\Provider\CustomerData;

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

        $customer = new Customer();
        $customer->setName($name);
        $customer->setDateOfBirth($dateOfBirth);
        $customer->setGender($gender);
        $customer->setKtp($ktp);
        $customer->setLoanAmount($loanAmount);
        $customer->setLoanPeriod($loanPeriod);
        $customer->setLoanPurpose($loanPurpose);
        
        $rules = $customer->customerValidator();
        
        $data = array(
            'name'  => $name,
            'dateOfBirth'  => $dateOfBirth,
            'ktp'  => $ktp,
            'loanAmount'  => $loanAmount,
            'loanPeriod'  => $loanPeriod,
            'loanPurpose'  => $loanPurpose,
            'gender'  => $gender,
        );

        
        foreach($data as $key => $val) {
            try{
                $rules->check($val);
            } catch(\InvalidArgumentException $e) {
                $errors = $e->getMessage();
                
                return $response->withJson($errors, 401);
                
            }
        }
        // Write data on database
        // $cust = new CustomerData();
        // $cust->saveData();

        // $sql = "SELECT * FROM customer WHERE customerId = 2";
        // $stmt = $this->db->query($sql);
        // // $stmt = $this->db->prepare($sql);
        // // $stmt->execute();
        // // $result = $stmt->fetchAll();
        // return $response->withJson(["status" => "success", "data" => $result], 200);

        
    }

    
}