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
vmJsApi::css('csomagolo.printview', 'administrator/components/com_virtuemart/assets/css/');
?>

<div class="order-print-area">

    <!-- Rendelés adatai -->
    <table class="order-details-table">
        <col width="34%">
        <col width="22%">
        <col width="22%">
        <col width="22%">

        <tr>
            <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_NUMBER'); ?></strong></td>
            <td><?php echo $this->orderDetails->order_number; ?></td>
            <td><?php echo ($this->orderDetails->isKisker) ? "<strong>KISKER</strong>" : ""; ?></td>
            <td><?php echo $this->orderDetails->virtuemart_order_id; ?></td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_DATE'); ?></strong></td>
            <td colspan="3"><?php echo $this->orderDetails->dateFormatted; ?></td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_STATE'); ?></strong></td>
            <td colspan="3">
                <?php echo JText::_($this->orderDetails->statusName); ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_SHIPMENT_METHOD'); ?></strong></td>
            <td colspan="3">
                <?php echo $this->orderDetails->shipmentMethod; ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_VENDOR'); ?></strong></td>
            <td colspan="3"><?php echo $this->orderDetails->recommender; ?></td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUPON_CODE'); ?></strong></td>
            <td colspan="3">
                <?php echo $this->orderDetails->coupon_code; ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUPON_DISCOUNT'); ?></strong></td>
            <td colspan="3">
                <?php echo number_format(round($this->orderDetails->coupon_discount), 0, ',', ' '); ?> Ft
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_BANKACCOUNT'); ?></strong></td>
            <td colspan="3">
                <?php
if ($this->orderDetails->virtuemart_paymentmethod_id == 6) {
    echo $this->orderDetails->paymentDesc;
}
?>
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_PAYMENT_METHOD'); ?></strong></td>
            <td colspan="3">
                <?php echo $this->orderDetails->paymentMethod; ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_TOTALSUM'); ?></strong></td>
            <td colspan="3">
                <?php echo number_format(round($this->orderDetails->order_total), 0, ',', ' '); ?> Ft</td>
        </tr>

    </table>

<!-- Kupon, ajánló, partner adatai -->
<table class="vendor-coupon-table">
        <col width="34%">
        <col width="66%">

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_VENDOR'); ?></strong></td>
            <td colspan="3"><?php echo $this->orderDetails->recommender; ?></td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUPON_CODE'); ?></strong></td>
            <td colspan="3">
                <?php echo $this->orderDetails->coupon_code; ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUPON_DISCOUNT'); ?></strong></td>
            <td colspan="3">
                <?php echo number_format(round($this->orderDetails->coupon_discount), 0, ',', ' '); ?> Ft
            </td>
        </tr>

    </table>

    <!-- Megjegyzések -->
    <table class="notes-table">
        <col width="50%">
        <col width="50%">
        <thead>
            <tr>
                <th><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_CUSTOMER_NOTE'); ?></strong></th>
                <!-- <th><strong>
                        <?php // echo JText::_('COM_VIRTUEMART_PRINTVIEW_GLS_NOTE'); ?></strong></th> -->
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="note">
                        <?php echo $this->orderDetails->customerNote; ?>
                    </div>
                </td>
                <!-- <td>
                    <div class="note">
                        <?php // echo $this->orderDetails->glsNote; ?>
                    </div>
                </td> -->
            </tr>
        </tbody>
    </table>

    <!-- Számlázási és szállítási cím -->
    <table class="address-table">
        <col width="20%">
        <col width="40%">
        <col width="40%">

        <thead>
            <tr>
                <th></th>
                <th><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_BILLING'); ?></strong></th>
                <th><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_SHIPPING'); ?></strong></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_EMAILADDRESS'); ?></strong></td>
                <td>
                    <?php echo $this->orderDetails->BT->email; ?>
                </td>
                <td>
                    <?php echo $this->orderDetails->ST->email; ?>
                </td>
            </tr>
            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_LASTNAME'); ?></strong></td>
                <td>
                    <?php echo $this->orderDetails->BT->lastName; ?>
                </td>
                <td>
                    <?php echo $this->orderDetails->ST->lastName; ?>
                </td>
            </tr>
            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_FIRSTNAME'); ?></strong></td>
                <td>
                    <?php echo $this->orderDetails->BT->firstName; ?>
                </td>
                <td>
                    <?php echo $this->orderDetails->ST->firstName; ?>
                </td>
            </tr>
            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ADDRESS'); ?></strong></td>
                <td>
                    <?php echo $this->orderDetails->BT->address; ?>
                </td>
                <td>
                    <?php echo $this->orderDetails->ST->address; ?>
                </td>
            </tr>
            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ZIPCODE'); ?></strong></td>
                <td>
                    <?php echo $this->orderDetails->BT->zip; ?>
                </td>
                <td>
                    <?php echo $this->orderDetails->ST->zip; ?>
                </td>
            </tr>
            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_CITY'); ?></strong></td>
                <td>
                    <?php echo $this->orderDetails->BT->city; ?>
                </td>
                <td>
                    <?php echo $this->orderDetails->ST->city; ?>
                </td>
            </tr>
            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUNTRY'); ?></strong></td>
                <td>
                    <?php echo $this->orderDetails->BT->country; ?>
                </td>
                <td>
                    <?php echo $this->orderDetails->ST->country; ?>
                </td>
            </tr>
            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_PHONE'); ?></strong></td>
                <td>
                    <?php echo $this->orderDetails->BT->phone; ?>
                </td>
                <td>
                    <?php echo $this->orderDetails->ST->phone; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Termékek tábla  -->
    <table class="products-table">
        <col width="25%">
        <col width="25%">
        <col width="25%">
        <col width="25%">
        <thead>
            <tr>
                <th colspan="3"><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_PRODUCT_NAME'); ?></strong></th>
                <th><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_QUANTITY'); ?></strong></th>
            </tr>
        </thead>
        <tbody>
            <?php
foreach ($this->orderDetails->orderItems as $orderItem) {
    echo "<tr>";
    echo "<td colspan=\"3\" align=\"center\">$orderItem->order_item_name</td>";
    echo "<td align=\"center\">$orderItem->product_quantity db</td>";
    echo "</tr>";
}
?>
            <tr class="payment-total">
                <td><strong>Részösszeg:</strong> <?php echo number_format(round($this->orderDetails->order_salesPrice), 0, ',', ' '); ?> Ft</td>
                <td><strong>Szállítási költség:</strong> <?php echo number_format(round($this->orderDetails->shipmentTotal), 0, ',', ' '); ?> Ft</td>
                <td><strong>Kuponjóváírás:</strong> <?php echo number_format(round($this->orderDetails->coupon_discount), 0, ',', ' '); ?> Ft</td>
                <td><strong>Összesen:</strong> <?php echo number_format(round($this->orderDetails->order_total), 0, ',', ' '); ?> Ft</td>
            </tr>
        </tbody>
    </table>

</div>


<script>
    window.print();
</script>