<?php

namespace Api\Models;

class BodyParser {

    public function getInput($request, $response, $args) {
        $parsedBody = $request->getParsedBody();
        
        $name = trim($parsedBody['name']);
        $dateOfBirth = $parsedBody['dateOfBirth'];
        $gender = $parsedBody['gender'];
        $ktp = $parsedBody['ktp'];
        $loanAmount = $parsedBody['loanAmount'];
        $loanPeriod = $parsedBody['loanPeriod'];
        $loanPurpose = $parsedBody['loanPurpose'];

        $validator = new Validation();

        // Set validation for name
        $nameRules = $validator->nameValidator($name);
        $user_data['name'] = $nameRules[1];

        // Set validation for date of birth
        $dobRules = $validator->dateOfBirthValidator($dateOfBirth);
        if($dobRules) {
            $dobArr = explode("/", $dateOfBirth);
            $date = $dobArr[0];
            $month = $dobArr[1];
            $year = $dobArr[2];
            $dob = $date . $month . $year;
        }
        
        // Set validation for gender
        $genderRules = $validator->genderValidator($gender, $date, $month, $year);
        $dobNew = $genderRules[1];
        
        // Set validation for KTP
        $ktpRules = $validator->ktpValidator($ktp, $dobNew);
        
        // Set validation for Loan Amount
        $loanAmountRules = $validator->loanAmountValidator($loanAmount);
        
        // Set validation for Loan period
        $loanPeriodRules = $validator->loanPeriodValidator($loanPeriod);

        // Set validation for Loan Purpose
        $loanPurposeRules = $validator->loanPurposeValidator($loanPurpose);
        

        $rules = array(
            'name'  => $nameRules[0],
            'date_of_birth'  => $dobRules,
            'ktp'  => $ktpRules,
            'loan_amount'  => $loanAmountRules,
            'loan_period'  => $loanPeriodRules,
            'loan_purpose'  => $loanPurposeRules,
            'gender'  => $genderRules[0],
        );

        $data = array(
            'name'  => $nameRules[1],
            'date_of_birth'  => $dateOfBirth,
            'ktp'  => $ktp,
            'loan_amount'  => $loanAmount,
            'loan_period'  => $loanPeriod,
            'loan_purpose'  => $loanPurpose,
            'gender'  => $gender,
        );

        
        foreach($data as $key => $val) {
            try{
                $rules[$key]->check($val);
                $user_data[$key] = $val;
            } catch(\InvalidArgumentException $e) {
                $errors = $e->getMessage();
                // $response->getBody()->write(json_encode($errors));
                
                return $response->withJson($errors, 401);
                
            }
        }

        // Write data on database
        $sql = "SELECT * FROM customer";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);

        // Write data on a file
        // $text = 'NEW DATA ' . PHP_EOL;
        // foreach($user_data as $k => $v) {
        //     $text .= ucfirst(str_replace("_", " ", $k)) . ' : ' . $request->getParam($k) . PHP_EOL ;
        // } 
        // $text .= '===========================' . PHP_EOL;

        // $file = fopen("file.txt","w");
        // fwrite($file, $text);
        // fclose($file);

        // return $response->withJson('Success. Registered data file has been created', 200);
      }

}