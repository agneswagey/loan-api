<?php

namespace App\Controllers;

use Respect\Validation\Exceptions\NestedValidationException as e;
use Api\Models\Customer;
use App\Provider\CustomerData;

class RegisterController extends Controller {

    public function register($request, $response) {

        $parsedBody = $request->getParsedBody();
        
        $firstName = trim($parsedBody['firstName']);
        $lastName = trim($parsedBody['lastName']);
        $dateOfBirth = $parsedBody['dateOfBirth'];
        $gender = $parsedBody['gender'];
        $ktp = $parsedBody['ktp'];
        $loanAmount = $parsedBody['loanAmount'];
        $loanPeriod = $parsedBody['loanPeriod'];
        $loanPurpose = $parsedBody['loanPurpose'];

        $customer = new Customer();
        $customer->setFirstName($firstName);
        $customer->setLastName($lastName);
        $customer->setDateOfBirth($dateOfBirth);
        $customer->setGender($gender);
        $customer->setKtp($ktp);
        $customer->setLoanAmount($loanAmount);
        $customer->setLoanPeriod($loanPeriod);
        $customer->setLoanPurpose($loanPurpose);
        
        $errors = false;

        try {
            $customer->customerValidator()->assert($customer);
        }
        catch (e $exception){

            foreach ($exception->getIterator() as $exception2) {
                $errors[] = $exception2->getMessage();
            }

        }

        if ($errors) {
            return $response->withJson(["error" => implode(', ', $errors)], 400);
        }
        else {
            // Write data on database
            $cust = new CustomerData($this->db);
            $message = $cust->saveData($customer);
            
            return $response->withJson(["status" => "success", "data" => $message], 200);
        }
        

        

        

    }

    
}