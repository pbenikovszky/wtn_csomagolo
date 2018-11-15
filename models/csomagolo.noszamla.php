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

// * FOR TESTS *

// define("XML_PATH", "\\myInvoices\\XMLs\\");
// define("INVOICE_PATH", "\\myInvoices\\");
// define("RESPONSE_PATH", "\\myInvoices\\responses\\");

// * FOR PROD *

define("XML_PATH", "/myInvoices/XMLs/");
define("INVOICE_PATH", "/myInvoices/");
define("RESPONSE_PATH", "/myInvoices/responses/");


/**
 * HelloWorldList Model
 *
 * @since  0.0.1
 */
class VirtueMartModelCsomagolo extends VmModel
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return the value of the states which should be shown in the main view
     */
    public function getOrderstates()
    {
        return array("Megerősített" => "C", "GLS csomagfeladásra vár" => "G", "Várakoztatva" => "V", "Kiszállítva" => "S");
    }

    /**
     * get the order details from printing
     * $orderID: the virtuemart_order_id identifier
     */
    public function getOrderById($orderID)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__virtuemart_orders'))
            ->where($db->quoteName('virtuemart_order_id') . ' LIKE ' . $db->quote($orderID));
        $db->setQuery($query);
        $result = $db->loadObject();

        // date of the order
        $result->dateFormatted= vmJsApi::date($result->created_on,'LC2',true);

        // total sum of order, formatted
        $result->order_totalSum = number_format(round($result->order_total), 0, ',', ' ');

        // get the order status
        $query = 'SELECT order_status_name FROM #__virtuemart_orderstates
				  WHERE order_status_code=' . $db->quote($result->order_status);
        $db->setQuery($query);
        $result->statusName = $db->loadResult();

        // get the user's shoppergroup id from the #__virtuemart_vmuser_shoppergroups table
        $query = 'SELECT virtuemart_shoppergroup_id FROM #__virtuemart_vmuser_shoppergroups s
                    WHERE s.virtuemart_user_id = ' . $db->quote($result->virtuemart_user_id);
        $db->setQuery($query);
        $isKisker = $db->loadResult();
        $result->isKisker = ($isKisker == 6);        

        // payment method
        $query = 'SELECT payment_name, payment_desc FROM #__virtuemart_paymentmethods_hu_hu
				  WHERE virtuemart_paymentmethod_id=' . $db->quote($result->virtuemart_paymentmethod_id);
        $db->setQuery($query);
        $paymentDetails = $db->loadObject();
        $result->paymentMethod = $paymentDetails->payment_name;
        $result->paymentDesc = $paymentDetails->payment_desc;

        // get currency details
        $query = 'SELECT currency_code_3, currency_symbol FROM #__virtuemart_currencies
                    WHERE virtuemart_currency_id=' . $db->quote($result->order_currency);
        $db->setQuery($query);
        $result->currency = $db->loadObject();

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
        //$result->glsNote = $userinfo[0]->gls_note;
        $result->adatkezeles = ($userinfo[0]->adatkezeles == 1) ? "Igen" : "Nem";
        $result->hirlevel = ($userinfo[0]->marketing == 1) ? "Igen" : "Nem";

        $query = 'SELECT fieldtitle FROM #__virtuemart_userfield_values
                    WHERE fieldvalue=' . $db->quote($userinfo[0]->honnanhallott);
        $db->setQuery($query);
        $result->honnanHallott = $db->loadResult();

        // Customer's detail: first & last name, email address, address
        $result->BT->firstName = $userinfo[0]->first_name;
        $result->BT->lastName = $userinfo[0]->last_name;
        $result->BT->email = $userinfo[0]->email;
        $result->BT->address = $userinfo[0]->address_1;
        $result->BT->city = $userinfo[0]->city;
        $result->BT->zip = $userinfo[0]->zip;
        $result->BT->phone = $userinfo[0]->phone_1;
        $result->BT->username = $userinfo[0]->username;

        $stIndex = count($userinfo) - 1;
        $result->ST->firstName = $userinfo[$stIndex]->first_name;
        $result->ST->lastName = $userinfo[$stIndex]->last_name;
        $result->ST->email = $userinfo[0]->email;
        $result->ST->address = $userinfo[$stIndex]->address_1;
        $result->ST->city = $userinfo[$stIndex]->city;
        $result->ST->zip = $userinfo[$stIndex]->zip;
        $result->ST->phone = $userinfo[$stIndex]->phone_1;
        $result->ST->username = $userinfo[$stIndex]->username;

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

        foreach ($result->orderItems as $orderItem) {
            $query = 'SELECT order_status_name FROM #__virtuemart_orderstates
                        WHERE order_status_code=' . $db->quote($orderItem->order_status);
            $db->setQuery($query);
            $orderItem->order_status_name = JText::_($db->loadResult());
        }

        $result->paymentTotal = floatval($result->order_payment) + floatval($result->order_payment_tax);
        $result->shipmentTotal = floatval($result->order_shipment) + floatval($result->order_shipment_tax);

        // get the order history
        $query = 'SELECT order_status_code, customer_notified, comments, modified_on FROM #__virtuemart_order_histories
                    WHERE virtuemart_order_id=' . $db->quote($orderID);
        $db->setQuery($query);
        $result->orderHistory = $db->loadObjectList();
        foreach ($result->orderHistory as $entry) {
            $entry->notifyCustomer = ($entry->customer_notified == 1 ? "Igen" : "Nem");
            $entryDate = strtotime($entry->modified_on);
            $entry->dateFormatted = strftime("%Y. %B %d, %A. %R", $entryDate);
            $query = 'SELECT order_status_name FROM #__virtuemart_orderstates
                        WHERE order_status_code=' . $db->quote($entry->order_status_code);
            $db->setQuery($query);
            $entry->order_status_name = JText::_($db->loadResult());
        }

        // Get shipment method details
        $query = 'SELECT * FROM #__virtuemart_shipment_plg_weight_countries
                    WHERE virtuemart_order_id=' . $db->quote($orderID);
        $db->setQuery($query);
        $result->shipmentDetails = $db->loadObject();
        $result->shipmentDetails->weight = $result->shipmentDetails->order_weight . ' ' . $result->shipmentDetails->shipment_weight_unit;

        $query = 'SELECT CONCAT(shipment_name, \'. \', shipment_desc) FROM #__virtuemart_shipmentmethods_hu_hu
                    WHERE virtuemart_shipmentmethod_id=' . $db->quote($result->shipmentDetails->virtuemart_shipmentmethod_id);
        $db->setQuery($query);
        $result->shipmentDetails->name = $db->loadResult();

        $result->shipmentDetails->tax = "27%";

        // Get payment method details
        $query = 'SELECT * FROM #__virtuemart_payment_plg_standard
                    WHERE virtuemart_order_id=' . $db->quote($orderID);
        $db->setQuery($query);
        $result->paymentDetails = $db->loadObject();

        // $result->paymentDetails->total = $result->paymentDetails->payment_order_total . ' ' . $result->paymentDetails->payment_currency;
        $result->paymentDetails->total = number_format(round($result->paymentDetails->payment_order_total, 2), 2, ',', ' ') . ' ' . $result->paymentDetails->payment_currency;

        $query = 'SELECT CONCAT(payment_name, \'. \', payment_desc) FROM #__virtuemart_paymentmethods_hu_hu
                    WHERE virtuemart_paymentmethod_id=' . $db->quote($result->paymentDetails->virtuemart_paymentmethod_id);
        $db->setQuery($query);
        $result->paymentDetails->name = $db->loadResult();

        // is coupon used
        $result->isCouponUsed = (abs($result->coupon_discount) > 0);

        // check if the order has recommendation
        $query = 'SELECT au.name FROM #__affiliate_tracker_conversions AS ac
                    LEFT JOIN #__affiliate_tracker_accounts AS au ON ac.atid = au.id 
                    WHERE reference_id=' . $db->quote($result->virtuemart_order_id);
        $db->setQuery($query);
        $db->query();
        if ($db->getNumRows() == 1) {
            $result->isRecommended = true;
            $result->recommender = $db->loadResult();
        } else {
            $result->isRecommended = false;
        };

        // return the object
        return $result;
    }

    /**
     * get the order's ID using the order_number field
     */
    public function getIdFromNumber($orderNumber)
    {

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

    public function getGlsOrder()
    {

        date_default_timezone_set('Europe/Paris');
        $datum = date("Y-m-d");
        $replace_rule = array('&amp;'=>'&',';'=>',',chr(10)=>" ",chr(13)=>" ");
        $prefix = "#__";
        
        // Create instances of Joomla's DB Object
        $db = JFactory::getDBO();
        $db2 = JFactory::getDBO();
        $db3 = JFactory::getDBO();
        $db5 = JFactory::getDBO();
        $db6 = JFactory::getDBO();



        $query = "SELECT a.*,b.virtuemart_order_userinfo_id, b.virtuemart_user_id, b.address_type, b.address_type_name, b.company, b.title, b.last_name, b.first_name, b.middle_name, b.phone_1, b.phone_2, b.fax, b.address_1, b.address_2, b.city, b.virtuemart_country_id, b.zip, b.email
        FROM
          " . $prefix . "virtuemart_orders a
          LEFT JOIN " . $prefix . "virtuemart_order_userinfos b on b.virtuemart_order_id = a.virtuemart_order_id
          LEFT JOIN " . $prefix . "virtuemart_order_histories h on h.virtuemart_order_id = a.virtuemart_order_id
        WHERE
          b.address_type = 'BT' AND a.order_status='G' GROUP BY a.virtuemart_order_id ORDER BY h.created_on DESC";

        $db->setQuery($query);
        $db->query() or die();
        $num_rows = $db->getNumRows();

        if ($num_rows == 0) {
            return "NA";
            exit();
        } else {
            $lista = 'Utánvét összege;cimzett;ország;irszam;város;cím;telefon;email;sms;cegnev;Utánvét hivatkozás;Ügyfél hivatkozás;Szolgáltatások;Megjegyzés';
            $lista .= "\r\n";
            $list2a = '';
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

                $query2 = "SELECT * FROM " . $prefix . "virtuemart_order_userinfos WHERE address_type = 'ST' AND virtuemart_order_id = " . $sor['virtuemart_order_id'] . " LIMIT 0,1";
                $db2->setQuery($query2);
                $db2->query() or die();
                $num_rows2 = $db2->getNumRows();

                if ($num_rows2 > 0) {
                    // Van külön szállítási cím
                    $szallitas = $db2->loadAssocList();
                    foreach ($szallitas as $szall_sor) {
                        $szall_cimzett = $szall_sor['first_name'] . " " . $szall_sor['last_name'];
                        if (strlen($szall_cimzett) < 2) {$szall_cimzett = $sor['first_name'] . " " . $sor['last_name'];}
                        $szall_orszag = 'Magyarország';
                        $szall_irszam = $szall_sor['zip'];
                        $szall_varos = $szall_sor['city'];
                        $szall_cim = $szall_sor['address_1'];
                        $szall_sms = $szall_telefon = $szall_sor['phone_1'];
                        if (strlen($szall_sms) < 2) {
                            $szall_sms = $szall_telefon = $sor['phone_1'];
                        }
                        $szall_email = $szall_sor['email'];
                        if (strlen($szall_email) < 2) {
                            $szall_email = $sor['email'];
                        }
                        $szall_cegnev = $szall_sor['company'];
                    }
                } else {
                    $szla_cimzett = $sor['first_name'] . " " . $sor['last_name'];
                    $szla_orszag = 'Magyarország';
                    $szla_irszam = $sor['zip'];
                    $szla_varos = $sor['city'];
                    $szla_cim = $sor['address_1'];
                    $szla_sms = $szla_telefon = $sor['phone_1'];
                    $szla_email = $sor['email'];
                    $szla_cegnev = $sor['company'];
                }

                $query5 = "SELECT * FROM " . $prefix . "virtuemart_orders WHERE virtuemart_order_id = " . $sor['virtuemart_order_id'] . " LIMIT 0,1";
                $db5->setQuery($query5);
                $db5->query() or die();
                $num_rows5 = $db5->getNumRows();
                $vUserId = "";
                $kisker = "";

                if ($num_rows5 > 0) {

                    $sor5 = $db5->loadAssocList();
                    $couponKod = $sor5[0]['coupon_code'];
                    $vUserId = $sor5[0]['virtuemart_user_id'];

                    if ($vUserId > 0) {

                        $query6 = "SELECT * FROM " . $prefix . "virtuemart_vmuser_shoppergroups WHERE virtuemart_user_id=$vUserId LIMIT 1";
                        $db6->setQuery($query6);
                        $db6->query() or die();
                        $num_rows6 = $db6->getNumRows();
                        if ($num_rows6 > 0) {
                            $sor6 = $db6->loadAssocList();
                            if ($sor6[0]['virtuemart_shoppergroup_id'] == 6) {
                                $kisker = "(kisker)";
                            }
                        }

                    } //if vUserId

                } else {
                    $couponKod = "NEM LEHET!!!!Viktornak szólni(171124)!";
                }

                $query6 = "SELECT * FROM " . $prefix . "affiliate_tracker_conversions WHERE reference_id = " . ($sor['virtuemart_order_id'] * 1) . " LIMIT 0,1";
                $db6->setQuery($query6);
                $db6->query() or die();
                $num_rows6 = $db6->getNumRows();
                if ($num_rows6 > 0) {

                    $sor6 = $db6->loadAssocList();

                    $query5 = "SELECT * FROM " . $prefix . "affiliate_tracker_accounts WHERE id = " . ($sor6[0]['atid'] * 1) . " LIMIT 0,1";
                    $db5->setQuery($query5);
                    $db5->query() or die();
                    $num_rows5 = $db5->getNumRows();
                    if ($num_rows5 > 0) {
                        $sor5 = $db5->loadAssocList();
                        if (trim($couponKod) != "") {
                            $couponKod .= " - ";
                        }

                        $couponKod .= $sor5[0]['account_name'];
                    } else {
                        $couponKod = "NEM LEHET!!!!Viktornak szólni(171123)!(" . $sor5[0]['atid'] . ")";
                    }

                }


                $query3 = "SELECT * FROM " . $prefix . "cloud_szamlazzhu_szamlaszam WHERE order_id = " . $sor['virtuemart_order_id'] . " LIMIT 0,1";
                $db3->setQuery($query3);
                $db3->query() or die();
                $num_rows3 = $db3->getNumRows();

                if ($num_rows3 > 0) {
                    $sor3 = $db3->loadAssocList();
                } else {
                    $sor3[0]['order_id'] = 'IMSERETLEN!!!';
                    $sor3[0]['osszeg'] = '0';
                    $sor3[0]['szamlaszam'] = date('Y') . '-';
                }
                $query3 = "SELECT * FROM " . $prefix . "virtuemart_orders WHERE virtuemart_order_id = " . $sor['virtuemart_order_id'] . " LIMIT 0,1";
                $db3->setQuery($query3);
                $db3->query() or die();
                $num_rows3 = $db3->getNumRows();
                if ($num_rows3 > 0) {
                    $sor4 = $db3->loadAssocList();
                    if ($sor4[0]['virtuemart_paymentmethod_id'] != 4) {
                        $sor3[0]['osszeg'] = '0';
                    } else {
                        $sor3[0]['osszeg'] = $sor4[0]['order_total'];
                    }
                }
            

                if (strlen($szall_sms) > 0) {
                    $telefon = $szall_sms;
                } else {
                    $telefon = $szla_sms;
                }

                if (strlen($szall_email) > 0) {
                    $email = $szall_email;
                } else {
                    $email = $szla_email;
                }

                if (strlen($szall_cimzett) > 0) {
                    $lista .= '' . $sor3[0]['osszeg'] . ';' . $szall_cimzett . ';' . $szall_orszag . ';' . $szall_irszam . ';' . $szall_varos . ';' . $szall_cim . ';' . $telefon . ';' . $email . ';' . $telefon . ';' . $szall_cegnev . ';' . $sor3[0]['szamlaszam'] . ';' . $sor3[0]['szamlaszam'] . ';sm2();';
                } else {
                    $lista .= '' . $sor3[0]['osszeg'] . ';' . $szla_cimzett . ';' . $szla_orszag . ';' . $szla_irszam . ';' . $szla_varos . ';' . $szla_cim . ';' . $telefon . ';' . $email . ';' . $telefon . ';' . $szla_cegnev . ';' . $sor3[0]['szamlaszam'] . ';' . $sor3[0]['szamlaszam'] . ';sm2();';
                }

                $guery = "UPDATE " . $prefix . "virtuemart_orders SET order_status = 'L' WHERE virtuemart_order_id =" . $sor['virtuemart_order_id'];
                $db->setQuery($query);
                $kesz = $db->query();
                if (!$kesz) {
                    echo "UPDATE hiba 1. - " . $sor['virtuemart_order_id'] . "<br />";
                    exit();
                }

                $guery = "UPDATE " . $prefix . "virtuemart_order_items SET order_status = 'L' WHERE virtuemart_order_id =" . $sor['virtuemart_order_id'];
                $db->setQuery($query);
                $kesz = $db->query();
                if (!$kesz) {
                    echo "UPDATE hiba 1. - " . $sor['virtuemart_order_id'] . "<br />";
                    exit();
                }

                $guery = "INSERT INTO " . $prefix . "virtuemart_order_histories (virtuemart_order_id,order_status_code,customer_notified,published,created_on,modified_on) VALUES ('" . $sor['virtuemart_order_id'] . "','L','0','1','" . date('Y-m-d H:i:s') . "','" . date('Y-m-d H:i:s') . "')";
                $db->setQuery($query);
                $kesz = $db->query();
                if (!$kesz) {
                    echo "INSERT hiba - " . $sor['virtuemart_order_id'] . "<br />";
                    exit();
                }

                $query = "SELECT * FROM " . $prefix . "virtuemart_order_userinfos WHERE virtuemart_order_id = " . $sor['virtuemart_order_id'] . " ORDER BY address_type LIMIT 1";
                $db->setQuery($query);
                $db->query() or die();
                $num_rows = $db->getNumRows();

                if ($num_rows > 0) {
                    $nev = $db->loadAssocList();
                }

                $query = "SELECT * FROM " . $prefix . "virtuemart_order_items WHERE virtuemart_order_id = " . $sor['virtuemart_order_id'];
                $db->setQuery($query);
                $db->query() or die();
                $num_rows = $db->getNumRows();

                $vesszo = "";

                if ($num_rows > 0) {
                    $rendelesTetel = $db->loadAssocList();
                    if ($nev[0]['first_name'] == $nev[0]['last_name']) {
                        $nevseg1 = $nev[0]['first_name'];
                    } else {
                        $nevseg1 = $nev[0]['first_name'] . " " . $nev[0]['last_name'];
                    }

                    $list2a .= $sor['virtuemart_order_id'] . ";$couponKod;" . strtr($kisker . $nevseg1, $replace_rule) . ";";

                    foreach ($rendelesTetel as $rend_sor) {
                        $list2a .= $vesszo . $rend_sor['virtuemart_product_id'] . "(" . $rend_sor['product_quantity'] . ")";
                        $vesszo = ",";
                    }

                    if (trim($nev[0]['customer_note']) == "") {
                        $seg1 = "-";
                    } else {
                        $seg1 = strtr($nev[0]['customer_note'], $replace_rule);
                    }
                    $list2a .= ";" . $seg1;

                    $lista .= ";" . strtr($nev[0]['customer_note'], $replace_rule) . "\r\n";
                    $list2a .= "\r\n"; //deldel<br>
                }

            }

        }

        if ($_SESSION['tss_vik_csinal'] == 1) {
            return $lista;
        } else {
            return $list2a;
        }

    }

// --------------------

    /**
     * Get the order from DB by searching for the order_number field
     */
    public function getOrderByNumber($orderNumber)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        // $query = 'SELECT * FROM #__virtuemart_orders WHERE virtuemart_order_id=1';
        $query->select('*')
            ->from($db->quoteName('#__virtuemart_orders'))
            ->where($db->quoteName('order_number') . ' LIKE ' . $db->quote($orderNumber));
        $db->setQuery($query);
        $result = $db->loadObject();

        // date of the order
        $result->dateFormatted= vmJsApi::date($result->created_on,'LC2',true);

        // total sum of order, formatted
        $result->order_totalSum = number_format(round($result->order_total), 0, ',', ' ');

        // return the object
        return $result;
    }

    /**
     * Set the state of the order which has the number $orderNumber to $newState
     */
    public function setOrder($orderNumber, $newState)
    {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $orderID = $this->getIdFromNumber($orderNumber);
        $order = $this->getOrderById($orderID);

        if ($order->isKisker && $order->virtuemart_paymentmethod_id != "11" && $newState === "S") {
            $newState = "W";
        }
        $result = $order->isKisker . ", payment method: " .$order->virtuemart_paymentmethod_id . ", newState: " . $newState;
        // change the status of the order
        $query = 'UPDATE #__virtuemart_orders
				  SET order_status=' . $db->quote($newState) . ' WHERE order_number=' . $db->quote($orderNumber);
        $db->setQuery($query);
        $db->execute();

        // change the status of the order items
        $query = 'UPDATE #__virtuemart_order_items
				  SET order_status=' . $db->quote($newState) . ' WHERE virtuemart_order_id=' . $db->quote($orderID);
        $db->setQuery($query);
        $db->execute();

        // record history

        $user = JFactory::getUser();
        $comment = "Csomagoló nézetből módosítva. Username: " . $user->username;

        $query = 'SELECT MAX(virtuemart_order_history_id)+1 FROM #__virtuemart_order_histories';
        $db->setQuery($query);
        $newID = $db->loadResult();

        $query = "INSERT INTO #__virtuemart_order_histories (virtuemart_order_history_id, virtuemart_order_id, order_status_code, customer_notified, comments, published, created_on, created_by, modified_on, modified_by)
                   VALUES ($newID, $orderID, '$newState', 0, '$comment', 1, NOW(), $user->id, NOW(), $user->id)";
        $db->setQuery($query);
        $db->execute();

        return $result;
    }

    public function setManualInvoiceFlag($orderNumber, $flagValue) {

        $orderID = $this->getIdFromNumber($orderNumber);
        
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = 'SELECT COUNT(*) FROM #__virtuemart_manualinvoices
                    WHERE virtuemart_order_id=' . $db->quote($orderID);
        $db->setQuery($query);
        $isInList = $db->loadResult();

        if ($isInList == 0) {
            // Insert a new row into the table
            $query = 'INSERT INTO #__virtuemart_manualinvoices
                    VALUES (' . $db->quote($orderID) . ', ' . $db->quote($flagValue) . ')';
        } else {
            // update the existing row in the table
            $query = 'UPDATE #__virtuemart_manualinvoices
                    SET manual_invoice=' . $db->quote($flagValue) . ' WHERE virtuemart_order_id=' . $db->quote($orderID);
        }

        $db->setQuery($query);
        $db->execute();

        return 1;

    }

    /**
     * Get the number of orders for each order status
     */
    function getStatusCounters() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query = 'SELECT COUNT(*) FROM #__virtuemart_orders 
                    WHERE order_status="C"';
        $db->setQuery($query);
        $result->countConfirmed = $db->loadResult();
        $query = 'SELECT COUNT(*) FROM #__virtuemart_orders 
                    WHERE order_status="G"';
        $db->setQuery($query);
        $result->countGLS = $db->loadResult();
        $query = 'SELECT COUNT(*) FROM #__virtuemart_orders 
                    WHERE order_status="V"';
        $db->setQuery($query);
        $result->countPending = $db->loadResult();

        return $result;
    }
    /**
     * Get the orders from DB for the main view
     */
    public function getOrders()
    {
        $db = JFactory::getDBO();

        // Change the status of orders to "Megerősített
        // where payment_method is "Utánvétes fizetés"

        // ! LOGIC ERROR
        // ! REMOVED, NEEDS TO BE FIXED
        /* $query = $db->getQuery(true);
        $query = 'UPDATE #__virtuemart_orders
        SET order_status="C"
        WHERE virtuemart_paymentmethod_id IN ("4", "6")';
        $db->setQuery($query);
        $db->execute(); */

        // Change the status of orders to "Megerősített
        // where payment_method is "Utánvétes fizetés"

        // get the list of orders

        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__virtuemart_orders'))
            ->where("order_status IN (\"C\", \"G\", \"V\")")
            ->order("created_on DESC");
        $db->setQuery($query);
        $result = $db->loadObjectList();

        foreach ($result as $line) {

            // get the user's name and email address from the #__virtuemart_order_userinfos table
            $query = 'SELECT first_name, last_name, middle_name, email, customer_note, virtuemart_user_id from #__virtuemart_order_userinfos u
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
            //$line->gls_note = $userinfo->gls_note;

            // get the user's shoppergroup id from the #__virtuemart_vmuser_shoppergroups table
            $query = 'SELECT virtuemart_shoppergroup_id FROM #__virtuemart_vmuser_shoppergroups s
						WHERE s.virtuemart_user_id = ' . $db->quote($userinfo->virtuemart_user_id);
            $db->setQuery($query);
            $isKisker = $db->loadResult();
            $line->isKisker = ($isKisker == 6);

            // set a formatted date
            // set the locale to hungarian
            setlocale(LC_ALL, 'hu_HU.utf8');
            $line->dateFormatted= vmJsApi::date($line->created_on,'LC2',true);
            

            // get the order state from the #__virtuemart_orderstates table
            $query = 'SELECT order_status_name from #__virtuemart_orderstates o
						WHERE o.order_status_code = ' . $db->quote($line->order_status);
            $db->setQuery($query);
            $orderinfo = $db->loadResult();
            $line->orderstate = $orderinfo;

            // check if order has invoice issued or not
            $query = 'SELECT szamlaszam FROM #__cloud_szamlazzhu_szamlaszam
						WHERE order_id=' . $line->virtuemart_order_id;
            $db->setQuery($query);
            $line->invoiceNumber = $db->loadResult();
            $line->hasInvoice = ($line->invoiceNumber != "");

            $line->isCouponUsed = (abs($line->coupon_discount) > 0);

            // get the manual invoice flag
            $query = 'SELECT manual_invoice FROM #__virtuemart_manualinvoices
                        WHERE virtuemart_order_id=' . $line->virtuemart_order_id;
            $db->setQuery($query);
            $line->manualInvoice = ($db->loadResult() == 1) ? true : false;

            // check if the order has recommendation
            $query = 'SELECT au.name FROM #__affiliate_tracker_conversions AS ac
                        LEFT JOIN #__affiliate_tracker_accounts AS au ON ac.atid = au.id 
                        WHERE reference_id=' . $db->quote($line->virtuemart_order_id);
            $db->setQuery($query);
            $db->query();
            if ($db->getNumRows() == 1) {
                $line->isRecommended = true;
                $line->recommender = $db->loadResult();
            } else {
                $line->isRecommended = false;
            };
        }

        

        return $result;
    }

    // * Print invoice

    public function getInvoicePDF($invoiceNumber, $order_id)
    {
        $xmlPath = $this->getInvoiceXML($invoiceNumber, $order_id);

        $path = getcwd();
        $pdfPath = $path . INVOICE_PATH;
        $pdfFullFileName = $pdfPath . $order_id . ".pdf";

        $ch = curl_init("https://www.szamlazz.hu/szamla/");
        $fp = fopen($pdfFullFileName, "w");

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_POST, true);

        $cFile = new CURLFile($xmlPath, "text/xml", "invoice_xml");
        $data = array('action-szamla_agent_pdf' => $cFile);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        fclose($fp);

        if ($response == true) {
            $result->result = "SUCCESS";
            $result->pdfFileName = $order_id . ".pdf";

            $db = JFactory::getDBO();
            $query = "SELECT keres_szam+1 FROM #__cloud_szamlazzhu_szamlaszam
                        WHERE order_id=$order_id";
            $db->setQuery($query);                
            $keres = $db->loadResult();

            $query = "UPDATE #__cloud_szamlazzhu_szamlaszam 
                        SET keres_szam=$keres, stamp=NOW()
                        WHERE order_id=$order_id";
            $db->setQuery($query);
            $db->execute();
        } else {
            $result->result = "FAIL";
        }

        curl_close($ch);

        return $result;
    }

    public function createInvoice($order_id, $eszamla = true) {

        $xmlPath = $this->createInvoiceXML($order_id, $eszamla);

        $path = getcwd();
        $responsePath = $path . RESPONSE_PATH;
        $responseFullFileName = $responsePath . $order_id . "_response.xml";        

        $ch = curl_init("https://www.szamlazz.hu/szamla/");
        $fp = fopen($responseFullFileName, "w");

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_POST, true);

        $cFile = new CURLFile($xmlPath, "text/xml", "invoice_xml");
        $data = array('action-xmlagentxmlfile' => $cFile);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        fclose($fp);

        if ($response == true) {
            $xmlResult = simplexml_load_file($responseFullFileName);
            if ($xmlResult->sikeres == "true") {
                $result->result = "SUCCESS";
                $result->invoiceNumber = $xmlResult->szamlaszam;
                $result->total = $xmlResult->szamlabrutto;
                $result->totalWithoutTax = $xmlResult->szamlanetto;
                $result->responseFileName = $order_id . "_response.xml";

                $db = JFactory::getDBO();
                $query = $db->getQuery(true);

                $query = "INSERT INTO #__cloud_szamlazzhu_szamlaszam 
                            VALUES ($order_id, '$result->invoiceNumber', $result->total, NOW(), NOW(), 0, '-')";
                $db->setQuery($query);
                $db->execute();
            } else {
                $result->result = "FAILXML";    
                $result->errorCode = $xmlResult->hibakod;
                $result->error = $xmlResult->hibauzenet;
            }
        } else {
            $result->result = "FAIL";
        }

        curl_close($ch);

        return $result;

    }

    public function getInvoiceXML($invoiceNumber, $order_id)
    {

        $componentParams = JComponentHelper::getParams('com_cloudszamlazzhu');
        $szamlazzhu_user = $componentParams->get('szamlazzhu_user', '');
        $szamlazzhu_pass = $componentParams->get('szamlazzhu_pass', '');
        $szamlazzhu_user = "fzs@wtn.hu";
        $szamlazzhu_pass = "Wtn-Proba";

        $szamla = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlszamlapdf xmlns="http://www.szamlazz.hu/xmlszamlapdf" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlszamlapdf xmlszamlapdf.xsd"></xmlszamlapdf>');
        $szamla->addChild('felhasznalo', $szamlazzhu_user);
        $szamla->addChild('jelszo', $szamlazzhu_pass);
        $szamla->addChild('szamlaszam', $invoiceNumber);
        $szamla->addChild('valaszVerzio', 1);

        $xml = $szamla->asXML();

        $path = getcwd();
        $xmlPath = $path . XML_PATH;
        $xmlFullFileName = $xmlPath . $order_id . ".xml";

        file_put_contents($xmlFullFileName, $xml);

        return $xmlFullFileName;

    }

    public function createInvoiceXML($order_id, $eszamla = true)
    {

        defined('JPATH_VM_ADMINISTRATOR') or define('JPATH_VM_ADMINISTRATOR', JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart');

        if (!class_exists('vmText')) {
            require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'config.php');
        }
           
        if (!class_exists('VirtueMartModelOrders')){
            require_once JPATH_ADMINISTRATOR.'/components/com_virtuemart/models/orders.php';
        }

        $componentParams = JComponentHelper::getParams('com_cloudszamlazzhu');
        $szamlazzhu_user = $componentParams->get('szamlazzhu_user', '');
        $szamlazzhu_pass = $componentParams->get('szamlazzhu_pass', '');

        $szamlazzhu_user = "fzs@wtn.hu";
        $szamlazzhu_pass = "Wtn-Proba";

        // get the language of the invoice
        $szlanyelv = $componentParams->get('szlanyelv', 1);
       
        if ($szlanyelv == 1) {
            $nyelv = 'hu';
        }else{
            $nyelv = 'en';
        }
        
        if($szamlazzhu_user == '' || $szamlazzhu_pass == ''){
            Throw new Exception('A szamlazz.hu felhasználónév vagy jelszó üres');
        }

        VmConfig::$vmlang=VmConfig::setdbLanguageTag();
        VmConfig::$vmlang='hu_hu';
        
        $orderModel = new VirtueMartModelOrders();
        
        $order = $orderModel->getOrder($order_id);
        
        $BT = $order["details"]['BT'];
        
        $fizhat_query = 'SELECT ifnull(`deadline`,8) as deadline'
                . '      FROM `#__cloud_szamlazzhu_payment_deadline` '
                . '      WHERE `virtuemart_paymentmethod_id` = '.intval($BT->virtuemart_paymentmethod_id);
        $db = JFactory::getDbo();
        $db->setQuery($fizhat_query);
        $res = $db->loadObject();
        
        if (count($res) == 0) {
            $fizhat = 8;
        }else {
            $fizhat = $res->deadline;
        }
        
        if (isset($order["details"]['ST'])){
            $ST = $order["details"]['ST'];
        }else{
            $ST = $BT;
        }
        
        $orderitems = $order['items'];
        
        $retval = new stdClass();
        $szamla = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlszamla xmlns="http://www.szamlazz.hu/xmlszamla" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlszamla xmlszamla.xsd"></xmlszamla>');
        
        $beallitasok = $szamla->addChild('beallitasok');

        $beallitasok->addChild('felhasznalo', $szamlazzhu_user);
        $beallitasok->addChild('jelszo', $szamlazzhu_pass);

        $beallitasok->addChild('eszamla', $eszamla ? "true" : "false");

        $beallitasok->addChild('kulcstartojelszo', '');

        $beallitasok->addChild('szamlaLetoltes', 'false');//viktuning
        $beallitasok->addChild('valaszVerzio', 2);//viktuning

        $fejlec = $szamla->addChild('fejlec');
        $fejlec->addChild('keltDatum', date('Y-m-d') );
        $fejlec->addChild('teljesitesDatum', date('Y-m-d', strtotime($this->getHistoryConfirmedDate($order['history']))));
        $fejlec->addChild('fizetesiHataridoDatum', date('Y-m-d', strtotime('+'.$fizhat.' days')));
        $fejlec->addChild('fizmod', $this->getPaymentMethodName($BT->virtuemart_paymentmethod_id));
        $fejlec->addChild('penznem', $this->getCurrencyName($BT->order_currency));
        $fejlec->addChild('szamlaNyelve', $nyelv);
        $fejlec->addChild('megjegyzes', 'A csomagolás termékdíj-kötelezettség az eladót terheli.');
        $fejlec->addChild('rendelesSzam', $BT->order_number);
        $fejlec->addChild('elolegszamla', 'false');
        $fejlec->addChild('vegszamla', 'false');
    
        $elado = $szamla->addChild('elado');
        $elado->addChild('bank', '');
        $elado->addChild('bankszamlaszam', '');
        $elado->addChild('emailReplyto', '');
        $elado->addChild('emailTargy', '');
        $elado->addChild('emailSzoveg', '');
        
        $nev = '';
        if ($BT->company != '') {
            $nev =  $BT->company;
        }else{
            if ($BT->middle_name != '') {
                $nev = $BT->first_name.' '.$BT->middle_name.' '.$BT->last_name;
            }else{
                $nev = $BT->first_name.' '.$BT->last_name;
            }
        }
        
        $vevo = $szamla->addChild('vevo');
        $vevo->addChild('nev', $nev);
        $vevo->addChild('irsz', $this->getCountryName($BT->virtuemart_country_id).'-'.$BT->zip);
        $vevo->addChild('telepules', $BT->city);
        $vevo->addChild('cim', $BT->address_1.' '.$BT->address_2);
        $vevo->addChild('email', $BT->email);
        $vevo->addChild('adoszam', '');

        $tetelek = $szamla->addChild('tetelek');
        
        foreach($orderitems as $item){
            $tetel = $tetelek->addChild('tetel');
            $tetel->addChild('megnevezes',$item->order_item_name);
            $tetel->addChild('mennyiseg',$item->product_quantity);
            $tetel->addChild('mennyisegiEgyseg','db');
            $tetel->addChild('nettoEgysegar',$item->product_discountedPriceWithoutTax);
            
            $calc = $this->getProductCalcNameAndValue($order['calc_rules'],$item->virtuemart_order_item_id,'VatTax');
            //$calc = $this->getProductCalcNameAndValue($order['calc_rules'],$item->virtuemart_order_item_id,'Tax');
            
            $tetel->addChild('afakulcs',intval($calc->calc_value));
            $tetel->addChild('nettoErtek',$item->product_discountedPriceWithoutTax*$item->product_quantity);
            $afaertek = $this->kerekit($this->kerekit($item->product_discountedPriceWithoutTax * $item->product_quantity) * ($calc->calc_value / 100));
            $tetel->addChild('afaErtek',$afaertek);
            $tetel->addChild('bruttoErtek',$this->kerekit($this->kerekit($item->product_discountedPriceWithoutTax * $item->product_quantity) *  (1 + ($calc->calc_value / 100))));
            $tetel->addChild('megjegyzes',$this->getProductDiscountName($order['calc_rules'],$item->virtuemart_order_item_id));
        }
        
        //szállítási mód
        if ($BT->order_shipment != 0) {//csak akkor kell a számlára rakni ha nem ingyenes volt a szállmód
            $tetel = $tetelek->addChild('tetel');
            $tetel->addChild('megnevezes',$this->getShipmentMethodName($BT->virtuemart_shipmentmethod_id));
            $tetel->addChild('mennyiseg',1);
            $tetel->addChild('mennyisegiEgyseg','db');
            $tetel->addChild('nettoEgysegar',$BT->order_shipment);
            $calc = $this->getNonProductCalcNameAndValue($order['calc_rules'],$BT->virtuemart_order_id,'shipment');
            
            $tetel->addChild('afakulcs',intval($calc->calc_value));
            $tetel->addChild('nettoErtek',$BT->order_shipment);
            $afaertek = $this->kerekit($this->kerekit($BT->order_shipment) * ($calc->calc_value / 100));
            $tetel->addChild('afaErtek',$afaertek);
            $tetel->addChild('bruttoErtek',$this->kerekit($this->kerekit($BT->order_shipment) *  (1 + ($calc->calc_value / 100))));
            $tetel->addChild('megjegyzes','');
        }
        
        
        //fizetési mód
        if ($BT->order_payment != 0) {//csak akkor kell a számlára rakni ha nem ingyenes volt a szállmód
            $tetel = $tetelek->addChild('tetel');
            $tetel->addChild('megnevezes',$this->getPaymentMethodName($BT->virtuemart_paymentmethod_id));
            $tetel->addChild('mennyiseg',1);
            $tetel->addChild('mennyisegiEgyseg','db');
            $tetel->addChild('nettoEgysegar',$BT->order_payment);
            $calc = $this->getNonProductCalcNameAndValue($order['calc_rules'],$BT->virtuemart_order_id,'payment');
            $tetel->addChild('afakulcs',intval($calc->calc_value));
            $tetel->addChild('nettoErtek',$BT->order_payment);
            $afaertek = $this->kerekit($this->kerekit($BT->order_payment) * ($calc->calc_value / 100));
            $tetel->addChild('afaErtek',$afaertek);
            $tetel->addChild('bruttoErtek',$this->kerekit($this->kerekit($BT->order_payment) *  (1 + ($calc->calc_value / 100))));
            $tetel->addChild('megjegyzes','');
        }
        //fizmod vége
        
        //kupon
        if ($BT->coupon_discount != 0) {//csak akkor kell a számlára rakni ha volt kupon
            $tetel = $tetelek->addChild('tetel');
            $tetel->addChild('megnevezes','Kupon kedvezmény. Kuponkód: '.$BT->coupon_code);
            $tetel->addChild('mennyiseg',1);
            $tetel->addChild('mennyisegiEgyseg','');
            $afaertek = $this->kerekit($this->kerekit($BT->coupon_discount) * 0.2126);
            $nettoertek = $this->kerekit($this->kerekit($BT->coupon_discount) - $afaertek);    
            $tetel->addChild('nettoEgysegar',$nettoertek);         
            $tetel->addChild('afakulcs',27);
            $tetel->addChild('nettoErtek',$nettoertek);
            $tetel->addChild('afaErtek',$afaertek);
            $tetel->addChild('bruttoErtek',$BT->coupon_discount);
            $tetel->addChild('megjegyzes', '');
        }
        //kupon vége

        
        $xml = $szamla->asXML();


        $path = getcwd();
        $xmlPath = $path . XML_PATH;
        
        $xmlFullFileName = $xmlPath . $order_id . "_create.xml";

        file_put_contents($xmlFullFileName, $xml);

        return $xmlFullFileName;

    }

    public function getProductDiscountName($calc_array, $virtuemart_order_item_id){
        $retval = '';
        $tmp = array();
        
        foreach($calc_array as $key => $calc){
            if($calc->virtuemart_order_item_id == $virtuemart_order_item_id && ($calc->calc_kind == 'DBTax' || $calc->calc_kind == 'DATax')  ) {
                $tmp[] = $calc->calc_rule_name;
            }
        }
        
        return implode(',', $tmp);
    }   
       
    
    function getCurrencyName($currency_id){
        $db = JFactory::getDbo();
        $query = 'select t.currency_code_3 from #__virtuemart_currencies t where t.virtuemart_currency_id = '.$currency_id;
        $db->setQuery($query);
        $res = $db->loadObject();
        
        if ($res->currency_code_3 == ''){
            return "HUF";
        }else{
            return $res->currency_code_3;
        }
        
    }
    
    function getCountryName($country_id){
        $db = JFactory::getDbo();
        $query = 'select t.country_name from #__virtuemart_countries t where t.virtuemart_country_id = '.$country_id;
        $db->setQuery($query);
        $res = $db->loadObject();
        
        return $res->country_name;
    }
    
    function getShipmentMethodName($shipmentmethod_id){
        $db = JFactory::getDbo();
        $query = 'select t.shipment_name from #__virtuemart_shipmentmethods_hu_hu t where t.virtuemart_shipmentmethod_id = '.$shipmentmethod_id;
        $db->setQuery($query);
        $res = $db->loadObject();
        
        return $res->shipment_name;
        
    }
    
    function getPaymentMethodName($paymentmethod_id){
        $db = JFactory::getDbo();
        $query = 'select t.payment_name from #__virtuemart_paymentmethods_hu_hu t where t.virtuemart_paymentmethod_id = '.$paymentmethod_id;
        $db->setQuery($query);
        $res = $db->loadObject();
        
        return $res->payment_name;
     }
    
    public function getHistoryConfirmedDate($historyarray){
        
        foreach($historyarray as $historyitem){
            if($historyitem->order_status_code == 'C'){
                return $historyitem->created_on;
            }
        }
        
        return false;
    }
    
    public function getProductCalcNameAndValue($calcarray,$order_item_id,$calckind){
        $retval = new stdClass();
        
        foreach($calcarray as $calc){
            //echo $calc->virtuemart_order_item_id.'-'.$order_item_id.'-'.strtolower($calc->calc_kind).' '. strtolower($calckind).'###';
            if($calc->virtuemart_order_item_id == $order_item_id && strtolower($calc->calc_kind) == strtolower($calckind)){
                $retval->calc_rule_name = $calc->calc_rule_name;
                $retval->calc_value = $calc->calc_value;
                
                return $retval;
            }
        }
        
        throw new Exception('A számítási szabályok között nem található '.$calckind);
    }
    /**
     * 
     * @param type $calcarray - virtuemart ordermodel calc array
     * @param type $order_id - virtuemart_order_id
     * @param type $itemKind - "payment" vagy "shipment"
     * @return \stdClass
     * @throws Exception
     */
    public function getNonProductCalcNameAndValue($calcarray,$order_id,$itemKind){
        $retval = new stdClass();
        
        foreach($calcarray as $calc){
            //echo $calc->virtuemart_order_item_id.'-'.$order_item_id.'-'.strtolower($calc->calc_kind).' '. strtolower($calckind);
            if($calc->virtuemart_order_id == $order_id && strtolower($calc->calc_kind) == strtolower($itemKind)){
                $retval->calc_rule_name = $calc->calc_rule_name;
                $retval->calc_value = $calc->calc_value;
                return $retval;
            }
        }
        
        throw new Exception('A számítási szabályok között nem található '.$itemKind);
    }
    
    
    
    public function kerekit($ertek){
        VmConfig::loadConfig();
        $kerekites_pontossag = VmConfig::get('salesPriceRounding');
 
        if ($kerekites_pontossag < 0) {
            // return $ertek;
            return number_format(round($ertek, 2), 4, ".", "");
        }else {
            return number_format(round($ertek, 2), $kerekites_pontossag, ".", "");
        }
        // return number_format(round($ertek, 2), 2, ".", "");
    }    

}
