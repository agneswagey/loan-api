<?php

namespace Api\Models;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException as e;
use Respect\Validation\Rules\AbstractRule;

class Customer {

    public $firstName;
    public $lastName;
    public $dateOfBirth;
    public $gender;
    public $ktp;
    public $loanAmount;
    public $loanPeriod;
    public $loanPurpose;

    public function setFirstName($input) {

        $this->firstName = $input;

    }

    public function getFirstName() {
        
        return $this->firstName;

    }

    public function setLastName($input) {

        $this->lastName = $input;

    }

    public function getLastName() {
        
        return $this->lastName;

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

        $genders = ['M', 'F'];
        $purposes = ['vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment'];

        $dobNew = $this->getDobNew($this->gender, $this->dateOfBirth);
        $ktpNew = $this->getKtpNew($this->ktp, $dobNew);

        return v::attribute('firstName', v::notEmpty()->stringType()->setName('First Name'))
            ->attribute('lastName', v::notEmpty()->stringType()->setName('Last Name'))
            ->attribute('dateOfBirth', v::notEmpty()->date('Y-m-d')->setName('Date of birth'))
            ->attribute('gender', v::notEmpty()->in($genders)->setName('Gender'))
            ->attribute('ktp', v::notEmpty()->numericVal()->equals($ktpNew)->length(16,16)->setName('KTP'))
            ->attribute('loanAmount', v::notEmpty()->intVal()->between(1000, 10000)->setName('Loan amount'))
            ->attribute('loanPeriod', v::notEmpty()->numericVal()->setName('Loan period'))
            ->attribute('loanPurpose', v::notEmpty()->in($purposes)->setName("Loan Purpose"));

    }

    public function getDobNew($input, $dateOfBirth) {

        $dateArr = explode('-', $dateOfBirth);
        if($input == 'F') {
            $dt = substr($dateArr[2], 0, 1);
            if($dt == "0") {
                $dt1 = (int) str_replace("0", "", $dateArr[2]);
            }
            $dtNew = $dt1 + 40;
            
            $dobNew = $dtNew . $dateArr[1] . substr($dateArr[0], 2);

        } else if($input == 'M') { 
            $dobNew = $dateArr[2] . $dateArr[1] . substr($dateArr[0], 2);
        }    

        return $dobNew;

    }

    public function getKtpNew($input, $dobNew) {

        $ktp1Str = substr($input, 0, 6);
        $ktp2Str = substr($input, 12, 4);   
        $ktpNew = $ktp1Str . $dobNew . $ktp2Str;
        
        return $ktpNew;

    }

}