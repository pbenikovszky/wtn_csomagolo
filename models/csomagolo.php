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


	// * GLS Export models

	public function getGlsOrder() {

		date_default_timezone_set('Europe/Paris');
		$datum = date("Y-m-d");

		// Create instances of Joomla's DB Object
		$db=JFactory::getDBO();
		$db2=JFactory::getDBO();
		$db3=JFactory::getDBO();
		$db5=JFactory::getDBO();
		$db6=JFactory::getDBO();

		$prefix = "#__";

		$query="SELECT a.*,b.virtuemart_order_userinfo_id, b.virtuemart_user_id, b.address_type, b.address_type_name, b.company, b.title, b.last_name, b.first_name, b.middle_name, b.phone_1, b.phone_2, b.fax, b.address_1, b.address_2, b.city, b.virtuemart_country_id, b.zip, b.email
        FROM 
          ".$prefix."virtuemart_orders a
          LEFT JOIN ".$prefix."virtuemart_order_userinfos b on b.virtuemart_order_id = a.virtuemart_order_id
          LEFT JOIN ".$prefix."virtuemart_order_histories h on h.virtuemart_order_id = a.virtuemart_order_id
        WHERE 
          b.address_type = 'BT' AND a.order_status='L' GROUP BY a.virtuemart_order_id ORDER BY h.created_on DESC";

		$db->setQuery($query);
		$db->query() or die();
		$num_rows = $db->getNumRows();
		
		if ($num_rows == 0) {
			return "Nincs listázásra váró rendelés! Statusza: GLS futárra vár!";
			exit();
		} else {
			$lista = 'Utánvét összege;cimzett;ország;irszam;város;cím;telefon;email;sms;cegnev;Utánvét hivatkozás;Ügyfél hivatkozás;Szolgáltatások;Megjegyzés';
			$lista .= "\r\n";
			$list2a='';
			$rendelesek = $db->loadAssocList();		
			foreach ($rendelesek as $sor) {
				$szall_cimzett = '';
				$szall_orszag = '';
				$szall_irszam = '';
				$szall_varos = '';
				$szall_cim = '';
				$szall_sms = '';
				$szall_email = '';
				$szall_cegnev = '';
				$szla_cimzett = '';
				$szla_orszag = '';
				$szla_irszam = '';
				$szla_varos = '';
				$szla_cim = '';
				$szla_sms = '';
				$szla_email = '';
				$szla_cegnev = '';
				
				$query2 = "SELECT * FROM ".$prefix."virtuemart_order_userinfos WHERE address_type = 'ST' AND virtuemart_order_id = ".$sor['virtuemart_order_id']." LIMIT 0,1";
				$db2->setQuery($query2);
				$db2->query() or die();
				$num_rows2 = $db2->getNumRows();
				
				if ($num_rows2 > 0) {
					// Van külön szállítási cím
					$szallitas = $db2->loadAssocList();
					foreach ($szallitas as $szall_sor) {
						$szall_cimzett = $szall_sor['first_name']." ".$szall_sor['last_name'];
						if (strlen($szall_cimzett)<2) { $szall_cimzett = $sor['first_name']." ".$sor['last_name'];}
						$szall_orszag = 'Magyarország';
						$szall_irszam = $szall_sor['zip'];
						$szall_varos = $szall_sor['city'];
						$szall_cim = $szall_sor['address_1'];
						$szall_sms = $szall_telefon = $szall_sor['phone_1'];
						IF (strlen($szall_sms)<2) {$szall_sms = $szall_telefon = $sor['phone_1'];}
						$szall_email = $szall_sor['email'];
						IF (strlen($szall_email)<2) {$szall_email = $sor['email'];}
						$szall_cegnev = $szall_sor['company'];
					}
				} else {
						$szla_cimzett = $sor['first_name']." ".$sor['last_name'];
						$szla_orszag = 'Magyarország';
						$szla_irszam = $sor['zip'];
						$szla_varos = $sor['city'];
						$szla_cim = $sor['address_1'];
						$szla_sms = $szla_telefon = $sor['phone_1'];
						$szla_email = $sor['email'];
						$szla_cegnev = $sor['company'];
				}
	

				$query5 = "SELECT * FROM ".$prefix."virtuemart_orders WHERE virtuemart_order_id = ".$sor['virtuemart_order_id']." LIMIT 0,1";
				$db5->setQuery($query5);
				$db5->query() or die();
				$num_rows5 = $db5->getNumRows();
				$vUserId="";$kisker="";

				if ($num_rows5 > 0) {
				
					$sor5 = $db5->loadAssocList();
					$couponKod=$sor5[0]['coupon_code'];
							$vUserId=$sor5[0]['virtuemart_user_id'];	

					if ($vUserId>0){

						$query6 = "SELECT * FROM ".$prefix."virtuemart_vmuser_shoppergroups WHERE virtuemart_user_id=$vUserId LIMIT 1";
						$db6->setQuery($query6);
						$db6->query() or die();
						$num_rows6 = $db6->getNumRows();
						if ($num_rows6 > 0 ) {
							$sor6 = $db6->loadAssocList();
							if ($sor6[0]['virtuemart_shoppergroup_id']==6) $kisker="(kisker)";
						}
				
					}//if vUserId
				  
	
				} else {
					$couponKod="NEM LEHET!!!!Viktornak szólni(171124)!";
				}
	  
				  $query6 = "SELECT * FROM ".$prefix."affiliate_tracker_conversions WHERE reference_id = ".($sor['virtuemart_order_id']*1)." LIMIT 0,1";
				  $db6->setQuery($query6);
				  $db6->query() or die();
				  $num_rows6 = $db6->getNumRows();
				  if ($num_rows6 > 0) {
					$sor6 = $db6->loadAssocList();

					$query5 = "SELECT * FROM ".$prefix."affiliate_tracker_accounts WHERE id = ".($sor6[0]['atid']*1)." LIMIT 0,1";
					$db5->setQuery($query5);
					$db5->query() or die();
					$num_rows5 = $db5->getNumRows();
					if ($num_rows5 > 0) {
						$sor5 = $db5->loadAssocList();
						if (trim($couponKod)!="") $couponKod.=" - ";
						$couponKod.=$sor5[0]['account_name'];
					}else $couponKod="NEM LEHET!!!!Viktornak szólni(171123)!(".$sor5[0]['atid'].")";
	

				  }
	

				$query3 = "SELECT * FROM ".$prefix."cloud_szamlazzhu_szamlaszam WHERE order_id = ".$sor['virtuemart_order_id']." LIMIT 0,1";
				$db3->setQuery($query3);
				$db3->query() or die();
				$num_rows3 = $db3->getNumRows();
				if ($num_rows3 > 0) {
				  $sor3 = $db3->loadAssocList();
				  $query3 = "SELECT * FROM ".$prefix."virtuemart_orders WHERE virtuemart_order_id = ".$sor['virtuemart_order_id']." LIMIT 0,1";
				  $db3->setQuery($query3);
				  $db3->query() or die();
				  $num_rows3 = $db3->getNumRows();
				  if ($num_rows3 > 0) {
					$sor4 = $db3->loadAssocList();
					if ($sor4[0]['virtuemart_paymentmethod_id']==6 or $sor4[0]['virtuemart_paymentmethod_id']==10) $sor3[0]['osszeg']='0';
				  }
				} else {$sor3[0]['order_id']='IMSERETLEN!!!'; $sor3[0]['osszeg']='0'; $sor3[0]['szamlaszam']=date('Y').'-';}//XXXX
	
	
				IF(strlen($szall_sms) > 0) {$telefon = $szall_sms;}
				ELSE {$telefon = $szla_sms;}
				IF(strlen($szall_email) > 0) {$email = $szall_email;}
				ELSE {$email = $szla_email;}
			 
				IF (strlen($szall_cimzett) > 0) {
					$lista .= ''.$sor3[0]['osszeg'].';'.$szall_cimzett.';'.$szall_orszag.';'.$szall_irszam.';'.$szall_varos.';'.$szall_cim.';'.$telefon.';'.$email.';'.$telefon.';'.$szall_cegnev.';'.$sor3[0]['szamlaszam'].';'.$sor3[0]['szamlaszam'].';sm2();';
				} ELSE {
					$lista .= ''.$sor3[0]['osszeg'].';'.$szla_cimzett.';'.$szla_orszag.';'.$szla_irszam.';'.$szla_varos.';'.$szla_cim.';'.$telefon.';'.$email.';'.$telefon.';'.$szla_cegnev.';'.$sor3[0]['szamlaszam'].';'.$sor3[0]['szamlaszam'].';sm2();';
				}

				$guery="UPDATE ".$prefix."virtuemart_orders SET order_status = 'L' WHERE virtuemart_order_id =".$sor['virtuemart_order_id'];
				$db->setQuery($query);
				$kesz = $db->query();
				IF (!$kesz) {echo "UPDATE hiba 1. - ".$sor['virtuemart_order_id']."<br />"; exit();}
	
				$guery="UPDATE ".$prefix."virtuemart_order_items SET order_status = 'L' WHERE virtuemart_order_id =".$sor['virtuemart_order_id'];
				$db->setQuery($query) ;
				$kesz = $db->query();
				IF (!$kesz) {echo "UPDATE hiba 1. - ".$sor['virtuemart_order_id']."<br />"; exit();}
	
				$guery="INSERT INTO ".$prefix."virtuemart_order_histories (virtuemart_order_id,order_status_code,customer_notified,published,created_on,modified_on) VALUES ('".$sor['virtuemart_order_id']."','L','0','1','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";        
				$db->setQuery($query);
				$kesz = $db->query();
				IF (!$kesz) {echo "INSERT hiba - ".$sor['virtuemart_order_id']."<br />"; exit();}
	
				$query = "SELECT * FROM ".$prefix."virtuemart_order_userinfos WHERE virtuemart_order_id = ".$sor['virtuemart_order_id']." ORDER BY address_type LIMIT 1";
				$db->setQuery($query);
				$db->query() or die();
				$num_rows = $db->getNumRows();
				if ($num_rows > 0) {
					$nev = $db->loadAssocList();
				}
	
				$query = "SELECT * FROM ".$prefix."virtuemart_order_items WHERE virtuemart_order_id = ".$sor['virtuemart_order_id'];
				$db->setQuery($query);
				$db->query() or die();
				$num_rows = $db->getNumRows();
	
				$vesszo="";
	
				if ($num_rows > 0) {
					$rendelesTetel = $db->loadAssocList();
					if ($nev[0]['first_name']==$nev[0]['last_name']) $nevseg1=$nev[0]['first_name']; else $nevseg1=$nev[0]['first_name'] ." ".$nev[0]['last_name'];
					$list2a.= $sor['virtuemart_order_id'].";$couponKod;".strtr($kisker.$nevseg1,$replace_rule).";";
	
				  foreach ($rendelesTetel as $rend_sor) {
					$list2a.= $vesszo.$rend_sor['virtuemart_product_id']."(".$rend_sor['product_quantity'].")"	;
					$vesszo=",";
				  }
				  if (trim($nev[0]['customer_note'])=="") $seg1="-";else $seg1=strtr($nev[0]['customer_note'],$replace_rule);
	
				  $list2a.= ";".$seg1;
	
				  $lista .=";".strtr($nev[0]['customer_note'],$replace_rule)."\r\n";
				  $list2a.="\r\n";//deldel<br>
				}
	
			}			
			
		}

		if ($_SESSION['vik_csinal'] == 1) {
			return $list2a;
		} else {
			return $lista;
		}

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