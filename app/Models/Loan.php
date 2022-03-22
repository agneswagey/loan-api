<?php

declare(strict_types=1);

namespace App\Models;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException as e;
use Respect\Validation\Rules\AbstractRule;

class Loan {

    private $loanAmount;
    private $loanPeriod;
    private $loanPurpose;

    public function setLoanAmount(int $loanAmount): void {
        
        $this->loanAmount = $loanAmount;

    }

    public function getLoanAmount(): int {
        
        return $this->loanAmount;

    }

    public function setLoanPeriod(int $loanPeriod): void {
        
        $this->loanPeriod = $loanPeriod;

    }

    public function getLoanPeriod(): int {
        
        return $this->loanPeriod;

    }

    public function setLoanPurpose(string $loanPurpose): void {
        
        $this->loanPurpose = $loanPurpose;

    }

    public function getLoanPurpose(): string {
        
        return $this->loanPurpose;

    }

    public function loanValidator() {

        $purposes = ['vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment'];

        return v::attribute('loanAmount', v::notEmpty()->intVal()->between(1000, 10000)->setName('Loan amount'))
            ->attribute('loanPeriod', v::notEmpty()->numericVal()->setName('Loan period'))
            ->attribute('loanPurpose', v::notEmpty()->in($purposes)->setName("Loan Purpose"));

    }

    public function setDataLoan($request) {

        $this->setLoanAmount($request['loanAmount']);
        $this->setLoanPeriod($request['loanPeriod']);
        $this->setLoanPurpose($request['loanPurpose']);

    }

}