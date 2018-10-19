<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HelloWorldList Model
 *
 * @since  0.0.1
 */
class VirtueMartModelCsomagolo extends VmModel
{

	function __construct() {
		parent::__construct();
	}

	public function getOrderstates() {
		return array("Megerősített"=>"C", "GLS futárra vár"=>"L", "Várakoztatva"=>"V");
	}

	/**
	 * get the order details from printing
	 * $orderID: the virtuemart_order_id identifier
	 */
	public function getOrderById($orderID) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// $query = 'SELECT * FROM #__virtuemart_orders WHERE virtuemart_order_id=1';
		$query->select('*')
			  ->from($db->quoteName('#__virtuemart_orders'))
			  ->where($db->quoteName('virtuemart_order_id') . ' LIKE ' . $db->quote($orderID));	
		$db->setQuery($query);
		$result = $db->loadObject();	

		// date of the order
		$d = strtotime($result->created_on);
		$result->dateFormatted =  strftime("%Y. %B %d, %A. %R", $d);

		// total sum of order, formatted
		$result->order_totalSum = number_format(round($result->order_total), 0, ',', ' ');
		
		// get the order status
		$query = 'SELECT order_status_name FROM #__virtuemart_orderstates
				  WHERE order_status_code=' . $db->quote($result->order_status);
		$db->setQuery($query);
		$result->statusName = $db->loadResult();

		// payment method 
		$query = 'SELECT payment_name, payment_desc FROM #__virtuemart_paymentmethods_hu_hu
				  WHERE virtuemart_paymentmethod_id=' . $db->quote($result->virtuemart_paymentmethod_id);
		$db->setQuery($query);
		$paymentDetails = $db->loadObject();
		$result->paymentMethod = $paymentDetails->payment_name;
		$result->paymentDesc = $paymentDetails->payment_desc;

		// shipment method 
		$query = 'SELECT shipment_name FROM #__virtuemart_shipmentmethods_hu_hu
				  WHERE virtuemart_shipmentmethod_id=' . $db->quote($result->virtuemart_shipmentmethod_id);
		$db->setQuery($query);
		$result->shipmentMethod = $db->loadResult();

		// Data from _virtuemart_order_userinfos table 
		$query = $db->getQuery(true);		
		$query->select('*')
			  ->from($db->quoteName('#__virtuemart_order_userinfos'))
			  ->where($db->quoteName('virtuemart_order_id') . ' LIKE ' . $db->quote($orderID));	
		$db->setQuery($query);
		$userinfo = $db->loadObjectList();

		// Customer notes
		$result->customerNote = $userinfo[0]->customer_note;
		$result->glsNote = $userinfo[0]->gls_note;

		// Customer's detail: first & last name, email address, address
		$result->BT->firstName = $userinfo[0]->first_name;
		$result->BT->lastName = $userinfo[0]->last_name;
		$result->BT->email = $userinfo[0]->email;
		$result->BT->address = $userinfo[0]->address_1;
		$result->BT->city = $userinfo[0]->city;
		$result->BT->zip = $userinfo[0]->zip;
		$result->BT->phone = $userinfo[0]->phone_1;

		$stIndex = count($userinfo) - 1;
		$result->ST->firstName = $userinfo[$stIndex]->first_name;
		$result->ST->lastName = $userinfo[$stIndex]->last_name;
		$result->ST->email = $userinfo[0]->email;
		$result->ST->address = $userinfo[$stIndex]->address_1;
		$result->ST->city = $userinfo[$stIndex]->city;
		$result->ST->zip = $userinfo[$stIndex]->zip;
		$result->ST->phone = $userinfo[$stIndex]->phone_1;

		// get the Country for the order address(es)
		$query = 'SELECT country_name FROM #__virtuemart_countries
				  WHERE virtuemart_country_id=' . $db->quote($userinfo[0]->virtuemart_country_id);
		$db->setQuery($query);
		$result->BT->country = $db->loadResult();

		$query = 'SELECT country_name FROM #__virtuemart_countries
				  WHERE virtuemart_country_id=' . $db->quote($userinfo[$stIndex]->virtuemart_country_id);
		$db->setQuery($query);
		$result->ST->country = $db->loadResult();		


		// Data from _virtuemart_order_items table
		$query = $db->getQuery(true);		
		$query->select('*')
			  ->from($db->quoteName('#__virtuemart_order_items'))
			  ->where($db->quoteName('virtuemart_order_id') . ' LIKE ' . $db->quote($orderID));	
		$db->setQuery($query);
		$result->orderItems = $db->loadObjectList();

		// return the object
		return $result;
	}

	/**
	 * get the order's ID using the order_number field
	 */
	public function getIdFromNumber($orderNumber) {

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('virtuemart_order_id')
			  ->from($db->quoteName('#__virtuemart_orders'))
			  ->where($db->quoteName('order_number') . ' LIKE ' . $db->quote($orderNumber));	
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;

	}

// --------------------


	public function getOrderByNumber($orderNumber) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// $query = 'SELECT * FROM #__virtuemart_orders WHERE virtuemart_order_id=1';
		$query->select('*')
			  ->from($db->quoteName('#__virtuemart_orders'))
			  ->where($db->quoteName('order_number') . ' LIKE ' . $db->quote($orderNumber));	
		$db->setQuery($query);
		$result = $db->loadObject();	

		// date of the order
		$d = strtotime($result->created_on);
		$result->dateFormatted =  strftime("%Y. %B %d, %A. %R", $d);

		// total sum of order, formatted
		$result->order_totalSum = number_format(round($result->order_total), 0, ',', ' ');
		
		// return the object
		return $result;
	}

	public function setOrder($orderNumber, $newState) {

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query = 'UPDATE #__virtuemart_orders 
				  SET order_status=' . $db->quote($newState) . ' WHERE order_number=' . $db->quote($orderNumber);
		$db->setQuery($query);
		$db->execute();
		return 1;
	}

	/**
	 * Get the orders from DB
	 */
	public function getOrders() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')
				->from($db->quoteName('#__virtuemart_orders'))
				->where("order_status IN (\"C\", \"L\", \"V\")");
		$db->setQuery($query);
		$result = $db->loadObjectList();

		foreach ($result as $line) {

			// get the user's name and email address from the #__virtuemart_order_userinfos table
			$query = 'SELECT first_name, last_name, middle_name, email, customer_note, virtuemart_user_id, gls_note from #__virtuemart_order_userinfos u
					  WHERE u.virtuemart_order_id=' . $line->virtuemart_order_id;
			$db->setQuery($query);
			$userinfo = $db->loadObject();

			// set the user_name (concat the first-middle-last name)
			$line->user_name = $userinfo->first_name . ' ' . $userinfo->middle_name . ' ' . $userinfo->last_name;
			// set the email address of the user
			$line->user_email = $userinfo->email;
			// set the customer's comment
            $line->comment = $userinfo->customer_note;
            // set the GLS comment
            $line->gls_note = $userinfo->gls_note;
			
			// get the user's shoppergroup id from the #__virtuemart_vmuser_shoppergroups table
			$query = 'SELECT virtuemart_shoppergroup_id FROM #__virtuemart_vmuser_shoppergroups s
					  WHERE s.virtuemart_user_id = ' . $db->quote($userinfo->virtuemart_user_id);
			$db->setQuery($query);
			$isKisker = $db->loadResult();
			$line->isKisker = ($isKisker == 6);
			
			// set a formatted date
			// set the locale to hungarian
			setlocale(LC_ALL, 'hu_HU.utf8');
			$d = strtotime($line->created_on);
			$line->dateFormatted =  strftime("%Y. %B %d, %A. %R", $d);

			// get the order state from the #__virtuemart_orderstates table
			$query = 'SELECT order_status_name from #__virtuemart_orderstates o
					  WHERE o.order_status_code = ' . $db->quote($line->order_status);
			$db->setQuery($query);
			$orderinfo = $db->loadResult();
			$line->orderstate = $orderinfo;

		}

		return $result;
	}

}