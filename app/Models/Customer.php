<?php

declare(strict_types=1);

namespace App\Models;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException as e;
use Respect\Validation\Rules\AbstractRule;

class Customer {

    private $firstName;
    private $lastName;
    private $dateOfBirth;
    private $gender;
    private $ktp;

    public function setFirstName(string $firstName): void {
        
        $this->firstName = $firstName;

    }

    public function getFirstName(): string {
        
        return $this->firstName;

    }

    public function setLastName(string $lastName): void {

        $this->lastName = $lastName;

    }

    public function getLastName(): string {
        
        return $this->lastName;

    }

    public function setDateOfBirth(string $dateOfBirth): void {
        
        $this->dateOfBirth = $dateOfBirth;

    }

    public function getDateOfBirth(): string {
        
        return $this->dateOfBirth;

    }

    public function setGender(string $gender): void {
        
        $this->gender = $gender;

    }

    public function getGender(): string {
        
        return $this->gender;

    }

    public function setKtp(string $ktp): void {
        
        $this->ktp = $ktp;

    }

    public function getKtp(): string {
        
        return $this->ktp;

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
            ->attribute('ktp', v::notEmpty()->intVal()->equals($ktpNew)->length(16,16)->setName('KTP'));

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

    public function setDataCustomer($request) {

        $this->setFirstName($request['firstName']); 
        $this->setLastName($request['lastName']);
        $this->setDateOfBirth($request['dateOfBirth']);
        $this->setGender($request['gender']);
        $this->setKtp($request['ktp']);

    }

}