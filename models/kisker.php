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

if (!class_exists('VmModel')) {
    require VMPATH_ADMIN . DS . 'helpers' . DS . 'vmmodel.php';
}

class VirtueMartModelKisker extends VmModel
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getOrders()
    {
        $db = JFactory::getDBO();

        // get the list of orders

        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__virtuemart_orders'))
            ->where("order_status IN (\"Y\")")
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
            $line->first_name = $userinfo->first_name;
            $line->last_name = $userinfo->last_name;
            $line->user_name = $userinfo->first_name . ' ' . $userinfo->middle_name . ' ' . $userinfo->last_name;
            // set the email address of the user
            $line->user_email = $userinfo->email;
            // set the customer's comment
            $line->comment = $userinfo->customer_note;

            // get the user's shoppergroup id from the #__virtuemart_vmuser_shoppergroups table
            $query = 'SELECT virtuemart_shoppergroup_id FROM #__virtuemart_vmuser_shoppergroups s
                            WHERE s.virtuemart_user_id = ' . $db->quote($userinfo->virtuemart_user_id);
            $db->setQuery($query);
            $isKisker = $db->loadResult();
            $line->isKisker = ($isKisker == 6);

            // set a formatted date
            // set the locale to hungarian
            setlocale(LC_ALL, 'hu_HU.utf8');
            $subject = vmJsApi::date($line->created_on, 'LC2', true);
            $search = '. ';
            $replace = '<br>';

            $replaceResult =
                strrev(implode(strrev($replace), explode(strrev($search), strrev($subject), 2)));
            $line->dateFormatted = str_replace(", ", " ", $replaceResult);

            // get the order state from the #__virtuemart_orderstates table
            $query = 'SELECT order_status_name from #__virtuemart_orderstates o
                            WHERE o.order_status_code = ' . $db->quote($line->order_status);
            $db->setQuery($query);
            $orderinfo = $db->loadResult();
            $line->orderstate = $orderinfo;

            // formatted order total
            $line->orderTotal = number_format(round($line->order_total), 0, ',', ' ') . " Ft";

            // Check the payment overdue conditions
            $line->isOverdue = false;

            // get the date from order history when state was changed to 'Y'
            $query = 'SELECT MAX(created_on) FROM #__virtuemart_order_histories
                        WHERE virtuemart_order_id=' . $db->quote($line->virtuemart_order_id);
            $db->setQuery($query);
            $dateChanged = $db->loadResult();

            $date = new DateTime("$dateChanged");
            $now = new DateTime();

            $line->dateDiff = intval($date->diff($now)->format("%a"));

            if ($line->order_total < 2000000) {
                if ($line->dateDiff > 9) {
                    $line->isOverdue = true;
                }
            } else {
                if ($line->dateDiff > 21) {
                    $line->isOverdue = true;
                }
            }

            // Date of changing to 'Kiskernek Kiszállítva' status
            //$line->dateShippedFormatted = str_replace(". ", "<br>", vmJsApi::date($dateChanged, 'LC2', true));
            $subject = vmJsApi::date($dateChanged, 'LC2', true);
            $search = '. ';
            $replace = '<br>';
            $replaceResult =
                strrev(implode(strrev($replace), explode(strrev($search), strrev($subject), 2)));
            $line->dateShippedFormatted = str_replace(", ", " ", $replaceResult);

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

            // Check if a coupon was used or not
            $line->isCouponUsed = (abs($line->coupon_discount) > 0);

        }

        return $result;
    }

    private function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);

    }

}
