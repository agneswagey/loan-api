<?php

namespace App\Controllers;

use \Slim\Views\Twig as View;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException as e;
use Respect\Validation\Rules\AbstractRule;
use Api\Models\Customer;
use App\Provider\CustomerData;

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
        
        try
        {
            $customer->customerValidator()->assert($customer);
        }
        catch(NestedValidationException $exception) {
            
            $message = $exception->getFullMessage();

            return $response->withJson(["status" => "success", "data" => $message], 200);

            // foreach ($exception->getIterator() as $exception2)
            // {
            //     $errors[] = $exception2->getMessage();
            // }
            
        }

        // Write data on database
        $cust = new CustomerData($this->db);
        $message = $cust->saveData($customer);
        
        return $response->withJson(["status" => "success", "data" => $message], 200);

        

    }

    
}