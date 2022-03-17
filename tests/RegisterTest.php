<?php

use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase {
    
    public function testRegisterSuccess() {

        $requestJson = ["firstName" => "Jevon", "lastName"  => "Tahapary", "dateOfBirth" => "2001-12-28", "gender" => "M", "ktp" => 3201022812010017, "loanAmount" => 2500, "loanPurpose" => "vacation", "loanPeriod" => 1];

        $expectedJson = ["firstName" => "Jevon", "lastName"  => "Tahapary", "dateOfBirth" => "2001-12-28", "gender" => "M", "ktp" => 3201022812010017, "loanAmount" => 2500, "loanPurpose" => "vacation", "loanPeriod" => 1];

        $this->assertJsonStringEqualsJsonString(json_encode($expectedJson), json_encode($requestJson));

    }

    public function testRegisterFailed() {

        $requestJson = ["firstName" => "Jevon", "lastName"  => "Tahapary", "dateOfBirth" => "2001-12-29", "gender" => "M", "ktp" => 3201022812010017, "loanAmount" => 2500, "loanPurpose" => "vacation", "loanPeriod" => 1];

        $expectedJson = ["firstName" => "Jevon", "lastName"  => "Tahapary", "dateOfBirth" => "2001-12-29", "gender" => "M", "ktp" => 3201022912010017, "loanAmount" => 2500, "loanPurpose" => "vacation", "loanPeriod" => 1];

        $this->assertJsonStringEqualsJsonString(json_encode($expectedJson), json_encode($requestJson));

    }

}