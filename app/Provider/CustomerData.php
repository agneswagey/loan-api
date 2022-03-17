<?php

namespace App\Provider;

use Api\Models\Customer;

class CustomerData {

    public $con;

    function __construct ($db) {
        
        $this->con = $db;

    } 

    public function saveData($customer) {

        $sql = "SELECT * FROM customer WHERE customerKtp = :ktp";
        $result = $this->con->prepare($sql);
        $result->bindParam('ktp', $customer->ktp);
        $result->execute();
        $numRows = $result->rowCount();
        
        if(empty($numRows)) {

            $sql = "INSERT INTO customer(customerFirstName,
                                        customerLastName,
                                        customerDob,
                                        customerGender,
                                        customerKtp,
                                        customerLoanAmount,
                                        customerLoanPeriod,
                                        customerLoanPurpose)
                            VALUES (:firstName,
                                    :lastName, 
                                    :dateOfBirth, 
                                    :gender,
                                    :ktp,
                                    :loanAmount,
                                    :loanPeriod,
                                    :loanPurpose)";
            
            $result = $this->con->prepare($sql);
            $result->bindParam('firstName', $customer->firstName);
            $result->bindParam('lastName', $customer->lastName);
            $result->bindParam('dateOfBirth', $customer->dateOfBirth);
            $result->bindParam('gender', $customer->gender);
            $result->bindParam('ktp', $customer->ktp);
            $result->bindParam('loanAmount', $customer->loanAmount);
            $result->bindParam('loanPeriod', $customer->loanPeriod);
            $result->bindParam('loanPurpose', $customer->loanPurpose);
            $result->execute();
            
            $message = "Data has been saved successfully";

        } else {

            $message = "Data is already exists";

        }
        
        return $message;

    }

}