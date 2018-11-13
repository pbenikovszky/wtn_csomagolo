<?php

$csomagoloModel = VmModel::getModel('csomagolo');
$orderNumberList = explode(",", $this->orderNumbers);
$orderIDs = array();

switch ($this->job) {
    case 'state-change':
        foreach ($orderNumberList as $orderNumber) {
            $current_order = $csomagoloModel->getOrderByNumber($orderNumber);
            $result = $csomagoloModel->setOrder($orderNumber, $this->newState);
            array_push($orderIDs, $order->virtuemart_order_id);
        }

        $response = json_encode(
            array("result" => "SUCCESS",
                "data" => $this->orderNumbers,
                "newState" => $this->newState,
                "resultState" => $result,
                "code" => 200));
        break;

    case 'manualinvoice':
        $csomagoloModel->setManualInvoiceFlag($this->orderNumbers, $this->flagValue);
        $response = json_encode(
            array("result" => "SUCCESS",
                "data" => $this->orderNumbers,
                "code" => 200)
        );
        break;
}

echo $response;
