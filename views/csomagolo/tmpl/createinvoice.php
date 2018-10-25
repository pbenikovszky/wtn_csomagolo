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

foreach ($orderNumbers as $orderNumber) {
    $oid = $csModel->getIdFromNumber($orderNumber);
    $response = $csModel->createInvoice($oid, false);
    if ($response->result == "SUCCESS") {
        echo "Számla sikeresen elkészítve a(z) $oid számú rendeléshez. Számlaszám: $response->invoiceNumber\n";
    } else {
        echo "$oid számú megrendeléshez a számlakészítés sikertelen!\n";
    }
}

// $response = $csModel->createInvoice($this->invoiceOrderID, false);

// if ($response->result == "SUCCESS") {
//     echo "<h1>Számla sikeresen létrehozva</h1>";
//     echo "<ul>";
//     echo "<li>Számla száma: " . $response->invoiceNumber . "</li>";
//     echo "<li>Számla nettó értéke: " . $response->totalWithoutTax . "</li>";
//     echo "<li>Számla bruttó értéke: " . $response->total . "</li>";
//     echo "<li>A szamlazz.hu oldal válasza sikeresen rögzítve: " . $response->responseFileName . "</li>";
//     echo "</ul>";
// } else if ($response->result == "FAILXML") {
//     echo "<h1>Számla létrehozása sikertelen</h1>";
//     echo "<p><strong>Hibakód $response->errorCode:</strong> <span>$response->error</span></p>";
// } else {
//     echo "Számlakészítés sikertelen!";
// }
