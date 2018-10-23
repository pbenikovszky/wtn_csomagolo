<?php

$csomagoloModel = VmModel::getModel('csomagolo');
$orderNumberList = explode(",", $this->orderNumbers);
$orderIDs = array();

foreach ($orderNumberList as $orderNumber) {
    $current_order = $csomagoloModel->getOrderByNumber($orderNumber);
    $csomagoloModel->setOrder($orderNumber, $this->newState);
    array_push($orderIDs, $order->virtuemart_order_id);
}

$response = json_encode(
    array("result" => "SUCCESS",
        "data" => $this->orderNumbers,
        "newState" => $this->newState,
        "code" => 200));

echo $response;

// no ending tag, pure php
