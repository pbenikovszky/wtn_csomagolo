<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage
 * @author VirtueMart Team, Max Milbers
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2016 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: orders.php 9522 2017-05-02 14:23:52Z StefanSTS $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JToolbarHelper::title("Számla létrehozása", 'csomagolo');

$csModel = VmModel::getModel('csomagolo');

$orderNumbers = explode(",", $this->invoiceOrderIDs);
$responseData = '';
foreach ($orderNumbers as $orderNumber) {
    $oid = $csModel->getIdFromNumber($orderNumber);
    $response = $csModel->createInvoice($oid, true);
    if ($response->result == "SUCCESS") {
        $responseData = $responseData . "Számla sikeresen elkészítve a(z) $oid számú rendeléshez. Számlaszám: $response->invoiceNumber\n";
    } else {
        $responseData = $responseData . "$oid számú megrendeléshez a számlakészítés sikertelen!\n";
    }
}

$response = json_encode(
    array("result" => "SUCCESS",
        "data" => $responseData,
        "code" => 200)
);

echo $response;
