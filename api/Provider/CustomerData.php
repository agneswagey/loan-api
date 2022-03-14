<?php

namespace Api\Provider;

use Api\Models\Customer;

class CustomerData extends Customer {

    public function saveData() {

        $customer = new Customer();
        $name = $customer->getName();
        // echo $name; exit;
        $sql = "SELECT * FROM customer WHERE customerId = 2";
        $stmt = $this->db->query($sql);
        // $stmt = $this->db->prepare($sql);
        // $stmt->execute();
        // $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);

    }

}