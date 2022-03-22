<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Loan;
use PDO;

class CustomerProvider {

    public $con;

    function __construct ($db) {
        
        $this->con = $db;

    } 

    public function saveData(Customer $customer, Loan $loan) {
       
        $firstName = $customer->getFirstName();
        $lastName = $customer->getLastName();
        $dateOfBirth = $customer->getDateOfBirth();
        $gender = $customer->getGender();
        $ktp = $customer->getKtp();
        $loanAmount = $loan->getLoanAmount();
        $loanPeriod = $loan->getLoanPeriod();
        $loanPurpose = $loan->getLoanPurpose();

        $sql = "SELECT * FROM customer WHERE customerKtp = :ktp";
        $result = $this->con->prepare($sql);
        $result->bindParam(':ktp', $ktp, PDO::PARAM_STR);
        $result->execute();
        $numRows = $result->rowCount();
        
        if(empty($numRows)) {

            $sqlCustomer = "INSERT INTO customer(customerFirstName, customerLastName, customerDob, customerGender, customerKtp) 
                VALUES (:firstName, :lastName, :dateOfBirth, :gender, :ktp)";        
            $resultCustomer = $this->con->prepare($sqlCustomer);
            $resultCustomer->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $resultCustomer->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $resultCustomer->bindParam(':dateOfBirth', $dateOfBirth, PDO::PARAM_STR);
            $resultCustomer->bindParam(':gender', $gender, PDO::PARAM_STR);
            $resultCustomer->bindParam(':ktp', $ktp, PDO::PARAM_STR);
            $resultCustomer->execute();

            $customerId = $this->con->lastInsertId();

            $sqlLoan = "INSERT INTO loan(customerId, loanAmount, loanPeriod, loanPurpose)
                VALUES (:customerId, :loanAmount, :loanPeriod, :loanPurpose)";
            $resultLoan = $this->con->prepare($sqlLoan);
            $resultLoan->bindParam(':customerId', $customerId, PDO::PARAM_INT);
            $resultLoan->bindParam(':loanAmount', $loanAmount, PDO::PARAM_INT);
            $resultLoan->bindParam(':loanPeriod', $loanPeriod, PDO::PARAM_INT);
            $resultLoan->bindParam(':loanPurpose', $loanPurpose, PDO::PARAM_STR);
            $resultLoan->execute();

            $message = "Data has been saved successfully";

        } else {

            $message = "Data is already exists";

        }
        
        return $message;

    }

}