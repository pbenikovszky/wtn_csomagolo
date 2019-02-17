<?php

$csomagoloModel = VmModel::getModel('csomagolo');
$countConfirmed = $csomagoloModel->getConfirmedCount();

$response = json_encode(
    array("result" => "SUCCESS",
        "data" => $countConfirmed));

echo $response;
