<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('VmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmviewadmin.php');

/**
 * HTML View class for the VirtueMart Component
 *
 * @package		VirtueMart
 * @author
 */
class VirtuemartViewCsomagolo extends VmViewAdmin {

	function display($tpl = null) {

		// get the instance of our model
		$csomagoloModel=VmModel::getModel('csomagolo');
		$orderNumberList = explode(",", $this->orderNumbers);
		$orderIDs = array(); 

		foreach ($orderNumberList as $orderNumber) {
			$current_order = $csomagoloModel->getOrderByNumber($orderNumber);
			$csomagoloModel->setOrder($orderNumber, $this->newState);
			array_push($orderIDs, $order->virtuemart_order_id);
		}

		$this->response = json_encode( 
			array("result" => "SUCCESS", 
				  "data" => $this->orderNumbers,
				  "newState" => $this->newState,
				  "code" => 200));


		// * GLS Export 
		$this->exportCSV = $csomagoloModel->getGlsOrder();

		parent::display($tpl);
	}



}

