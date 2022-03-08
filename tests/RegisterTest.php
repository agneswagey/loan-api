<?php

use PHPUnit\Framework\TestCase;
use \App\Validation\Rules\LoanAmount;
use \App\Validation\Rules\FirstLastName;
use \App\Validation\Rules\LoanPurpose;
use \App\Validation\Rules\KTP;

class RegisterTest extends TestCase {
    
    public function testLoanAmount() {
        $loan = new LoanAmount();
        $isValid = $loan->isValid(1200);

        $this->assertEquals(true, $isValid); 
    }

    public function testNameCount() {
        $name = new FirstLastName();
        $count = $name->countNameWords('Putri Ayu');

        $this->assertGreaterThanOrEqual(2, $count);
    }

    public function testCheckLoanPurpose() {
        $purpose = new LoanPurpose();
        $loanPurpose = $purpose->getLoanPurpose();

        $this->assertContains('renovation', $loanPurpose);

    }

}