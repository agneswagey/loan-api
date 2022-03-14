<?php

namespace Api\Models;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException as e;
use Respect\Validation\Rules\AbstractRule;

class Customer {
    public $name;
    public $dateOfBirth;
    public $gender;
    public $ktp;
    public $loanAmount;
    public $loanPeriod;
    public $loanPurpose;

    public function setName($input) {
        $this->name = $input;
    }

    public function getName() {
        return $this->name;
    }

    public function setDateOfBirth($input) {
        $this->dateOfBirth = $input;
    }

    public function getDateOfBirth() {
        return $this->dateOfBirth;
    }

    public function setGender($input) {
        $this->gender = $input;
    }

    public function getGender() {
        return $this->gender;
    }

    public function setKtp($input) {
        $this->ktp = $input;
    }

    public function getKtp() {
        return $this->ktp;
    }

    public function setLoanAmount($input) {
        $this->loanAmount = $input;
    }

    public function getLoanAmount() {
        return $this->loanAmount;
    }

    public function setLoanPeriod($input) {
        $this->loanPeriod = $input;
    }

    public function getLoanPeriod() {
        return $this->loanPeriod;
    }

    public function setLoanPurpose($input) {
        $this->loanPurpose = $input;
    }

    public function getLoanPurpose() {
        return $this->loanPurpose;
    }

    public function customerValidator() {
        $genders = array('M', 'F');
        $purposes = array('vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment');

        return v::attribute('name', v::stringType()->length(2, null)->setName('Name'))
            ->attribute('dateOfBirth', v::notEmpty()->date('d/m/y')->setName('Date of birth'))
            ->attribute('gender', v::notEmpty()->in($genders)->setName('Gender'))
            ->attribute('loanAmount', v::notEmpty()->intVal()->between(1000, 10000)->setName('Loan amount'))
            ->attribute('loanPeriod', v::notEmpty()->numericVal()->setName('Loan period'))
            ->attribute('loanPurpose', v::notEmpty()->in($purposes)->setName("Loan Purpose"));

    }

    public function ktpValidator($input, $dobNew) {
        $ktp1Str = substr($input, 0, 6);
        $ktp2Str = substr($input, 12, 4);   
        $ktpFull = $ktp1Str . $dobNew . $ktp2Str;
        
        return v::notEmpty()->numericVal()->equals($ktpFull)->length(16,16)->setName('KTP');
    }

}