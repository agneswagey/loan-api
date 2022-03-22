<?php

namespace App\Controllers;

use Respect\Validation\Exceptions\NestedValidationException as e;
use App\Models\Customer;
use App\Models\Loan;
use App\Providers\CustomerProvider;

class RegisterController extends Controller {

    private $parsedBody;

    public function register($request, $response) {

        $this->parsedBody = $request->getParsedBody();
        
        $customer = new Customer();
        $customer->setDataCustomer($this->parsedBody);

        $loan = new Loan();
        $loan->setDataLoan($this->parsedBody);
                
        $errors = false;

        try {
            $customer->customerValidator()->assert($customer);
            $loan->loanValidator()->assert($loan);
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
            // Insert data into database
            $cust = new CustomerProvider($this->db);
            $message = $cust->saveData($customer, $loan);
            
            return $response->withJson(["status" => "success", "data" => $message], 200);
        }
        

    }

}