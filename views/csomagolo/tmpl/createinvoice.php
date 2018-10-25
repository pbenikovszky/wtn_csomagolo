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

echo "<h1>$this->msg</h1>";

echo "<h2>$this->invoiceOrderID</h2>";

$response = $csModel->createInvoice($this->invoiceOrderID, false);

if ($response->result == "SUCCESS") {
    echo "<p>A számla elkészült, az elkészített számla száma: " . $response->invoiceNumber . "</p>";
    echo "<p>Response file: " . $response->responseFileName . "</p>";
} else {
    echo "Számlakészítés sikertelen!";
}
