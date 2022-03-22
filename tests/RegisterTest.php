<?php

use Slim\Http\Environment;
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\Headers;
use Slim\Http\Uri;
use PHPUnit\Framework\TestCase;
use App\Models\Customer;
use App\Controllers\RegisterController;
use Respect\Validation\Exceptions\NestedValidationException as e;
use Psr\Http\Message\UploadedFileInterface;


require_once './app/application.php';

class RegisterTest extends TestCase {
    
    private $app;
    
    public function setUp() : void
    {
        $_SESSION = array();
        $this->app = new \Slim\App();
        
    }

    public function requestFactory($url, $method) {
        $env = Environment::mock([
          'SCRIPT_NAME' => '/index.php',
          'REQUEST_URI' => $url,
          'REQUEST_METHOD' => $method,
        ]);
        $uri = Uri::createFromString('/register');
        $headers = Headers::createFromEnvironment($env);
        $cookies = [];
        $serverParams = $env->all();
        $body = new RequestBody();
        // $uploadedFiles = UploadedFile::createFromEnvironment($env);
        $request = new Request('GET', $uri, $headers, $cookies, $serverParams, $body);
        return $request;
    }

    public function testRegisterSuccess() {
        $requestBody = ["firstName" => "Jevon", "lastName"  => "Tahapary", "dateOfBirth" => "2001-12-29", "gender" => "M", "ktp" => 3201022812010017, "loanAmount" => 2500, "loanPurpose" => "vacation", "loanPeriod" => 1];

        $request = $this->requestFactory('/register', 'POST');

        $body = new RequestBody();
        $request->getParsedBody(json_encode($requestBody));
        $request->getBody()->rewind();
        
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('POST');

        $app = new \Slim\App();
        $path = '/register';
        $callable = function ($req, $res) {
            return $res->write($req->getParsedBody());
        };

        $app->get($path, $callable);

        $resOut = $app($request, new Response());
        $resOut->getBody()->rewind();

        $this->assertEquals('KTP must equal "3201022912010017"', $resOut->getBody()->getContents());
    }


    // private function loadEndpoint($url) {
    //     $request = ["firstName" => "Jevon", "lastName"  => "Tahapary", "dateOfBirth" => "2001-12-29", "gender" => "M", "ktp" => 3201022812010017, "loanAmount" => 2500, "loanPurpose" => "vacation", "loanPeriod" => 1];

    //     $json = json_encode($request);

    //     $ch = curl_init(); 
    //     curl_setopt($ch, CURLOPT_URL, $url); 
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //         'Content-Type: application/json',
    //         'Content-Length: ' . strlen($json))
    //     );
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     $output = curl_exec($ch);   
    //     $info = curl_getinfo($ch);
    //     curl_close($ch);
    //     return array(
    //       'body' => $output,
    //       'info' => $info
    //     );
    // }

    // public function testGetUserResponse() {

    //     $url = $this->api_url."/register";
    //     $response = $this->loadEndpoint($url);
    //     $resp = json_decode($response['body']);
    //     $output = $resp->API_Response->Status->Message->error; 
        
    //     $this->assertEquals('KTP must equal "3201022912010017"', $output);
    // }

   
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