<?php

namespace App\Provider;

use Api\Models\Customer;

class CustomerData {

    public $con;

    function __construct ($db) {
        
        $this->con = $db;

    } 

    public function saveData($customer) {

        $sql = "SELECT * FROM customer WHERE customerKtp = '" . $customer->ktp . "'";
        $result = $this->con->query($sql);
        $numRows = $result->rowCount();
        
        if(empty($numRows)) {

            $sql = "INSERT INTO customer(customerName,
                                        customerDob,
                                        customerGender,
                                        customerKtp,
                                        customerLoanAmount,
                                        customerLoanPeriod,
                                        customerLoanPurpose)
                            VALUES ('".$customer->name."', 
                                    '".$customer->dateOfBirth."', 
                                    '".$customer->gender."',
                                    '".$customer->ktp."',
                                    '".$customer->loanAmount."',
                                    '".$customer->loanPeriod."',
                                    '".$customer->loanPurpose."')";
            
            $result = $this->con->query($sql);
            $message = "Data has been saved successfully";

        } else {

            $message = "Data is already exists";

        }
        
        return $message;

    }

}