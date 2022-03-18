<?php

use Slim\Http\Environment;
use PHPUnit\Framework\TestCase;
use Api\Models\Customer;
use App\Controllers\RegisterController;
use Respect\Validation\Exceptions\NestedValidationException as e;

require_once './app/application.php';

class RegisterTest extends TestCase {
    
    private $app;
    protected $api_url = "http://localhost/new-api/public/";

    public function setUp() : void
    {
        $_SESSION = array();
        $this->app = new \Slim\App();
        
    }

    private function loadEndpoint($url) {
        $request = ["firstName" => "Jevon", "lastName"  => "Tahapary", "dateOfBirth" => "2001-12-29", "gender" => "M", "ktp" => 3201022812010017, "loanAmount" => 2500, "loanPurpose" => "vacation", "loanPeriod" => 1];

        $json = json_encode($request);

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json))
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);   
        $info = curl_getinfo($ch);
        curl_close($ch);
        return array(
          'body' => $output,
          'info' => $info
        );
    }

    public function testGetUserResponse() {

        $url = $this->api_url."/register";
        $response = $this->loadEndpoint($url);
        $resp = json_decode($response['body']);
        $output = $resp->API_Response->Status->Message->error; 
        
        $this->assertEquals('KTP must equal "3201022912010017"', $output);
      }

    // public function testRegisterSuccess() {
        
    //     $request = ["firstName" => "Jevon", "lastName"  => "Tahapary", "dateOfBirth" => "2001-12-29", "gender" => "M", "ktp" => 3201022812010017, "loanAmount" => 2500, "loanPurpose" => "vacation", "loanPeriod" => 1];

    //     $json = json_encode($request);

    //     $customer = new Customer();
    //     $customer->setDataCustomer($request);
        
    //     Environment::mock(array(
    //         'REQUEST_METHOD' => 'POST',
    //         'PATH_INFO' => '/register',
    //         'slim.input' => $json
    //     ));
        
    //     $this->assertTrue($customer->customerValidator()->validate($customer));
    // }

    // public function testRegisterFailed() {
        
    //     $request = ["firstName" => "Jevon", "lastName"  => "Tahapary", "dateOfBirth" => "2001-12-29", "gender" => "M", "ktp" => 3201022812010017, "loanAmount" => 2500, "loanPurpose" => "vacation", "loanPeriod" => 1];

    //     $json = json_encode($request);

    //     $customer = new Customer();
    //     $customer->setDataCustomer($request);
    //     $errors = false;
    //     try {
    //         $customer->customerValidator()->assert($customer);
    //     }
    //     catch (e $exception){

    //         foreach ($exception->getIterator() as $exception2) {
    //             $errors[] = $exception2->getMessage();
    //         }

    //     }
       
    //     Environment::mock(array(
    //         'REQUEST_METHOD' => 'POST',
    //         'PATH_INFO' => '/register',
    //         'slim.input' => $json
    //     ));
        
    //     $this->assertEquals('KTP must equal "3201022912010017"', $errors[0]);
    // }

}