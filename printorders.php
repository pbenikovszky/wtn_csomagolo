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


<?php
$orderCount = count($this->ordersToPrint);
$cnt = 1;
foreach ($this->ordersToPrint as $order) {?>

        <div class="order-print-area">

            <!-- Rendelés adatai -->
        <table class="order-details-table">
            <col width="34%">
            <col width="22%">
            <col width="22%">
            <col width="22%">

            <tr>
                <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_NUMBER'); ?></strong></td>
                <td><?php echo $order->order_number; ?></td>
                <td><?php echo ($order->isKisker) ? "<strong>KISKER</strong>" : ""; ?></td>
                <td><?php echo $order->virtuemart_order_id; ?></td>
            </tr>

            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_DATE'); ?></strong></td>
                <td colspan="3"><?php echo $order->dateFormatted; ?></td>
            </tr>

            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_STATE'); ?></strong></td>
                <td colspan="3">
                    <?php echo JText::_($order->statusName); ?>
                </td>
            </tr>

            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_SHIPMENT_METHOD'); ?></strong></td>
                <td colspan="3">
                    <?php echo $order->shipmentMethod; ?>
                </td>
            </tr>

            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_VENDOR'); ?></strong></td>
                <td colspan="3"><?php echo $order->recommender; ?></td>
            </tr>

            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUPON_CODE'); ?></strong></td>
                <td colspan="3">
                    <?php echo $order->coupon_code; ?>
                </td>
            </tr>

            <tr>
                <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUPON_DISCOUNT'); ?></strong></td>
                <td colspan="3">
                    <?php echo number_format(round($order->coupon_discount), 0, ',', ' '); ?> Ft
                </td>
            </tr>

            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_BANKACCOUNT'); ?></strong></td>
                <td colspan="3">
                    <?php
if ($order->virtuemart_paymentmethod_id == 6) {
    echo $order->paymentDesc;
}
    ?>
                </td>
            </tr>

            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_PAYMENT_METHOD'); ?></strong></td>
                <td colspan="3">
                    <?php echo $order->paymentMethod; ?>
                </td>
            </tr>

            <tr>
                <td><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_TOTALSUM'); ?></strong></td>
                <td colspan="3">
                    <?php echo number_format(round($order->order_total), 0, ',', ' '); ?> Ft</td>
            </tr>

    </table>

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
                    <thead>
                        <tr>
                            <th><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_CUSTOMER_NOTE'); ?></strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div class="note"><?php echo $order->customerNote; ?></div></td>
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
                            <th><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_BILLING'); ?></strong></th>
                            <th><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_SHIPPING'); ?></strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_EMAILADDRESS'); ?></strong></td>
                            <td><?php echo $order->BT->email; ?></td>
                            <td><?php echo $order->ST->email; ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_LASTNAME'); ?></strong></td>
                            <td><?php echo $order->BT->firstName; ?></td>
                            <td><?php echo $order->ST->firstName; ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_FIRSTNAME'); ?></strong></td>
                            <td><?php echo $order->BT->lastName; ?></td>
                            <td><?php echo $order->ST->lastName; ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ADDRESS'); ?></strong></td>
                            <td><?php echo $order->BT->address; ?></td>
                            <td><?php echo $order->ST->address; ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ZIPCODE'); ?></strong></td>
                            <td><?php echo $order->BT->zip; ?></td>
                            <td><?php echo $order->ST->zip; ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_CITY'); ?></strong></td>
                            <td><?php echo $order->BT->city; ?></td>
                            <td><?php echo $order->ST->city; ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUNTRY'); ?></strong></td>
                            <td><?php echo $order->BT->country; ?></td>
                            <td><?php echo $order->ST->country; ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_PHONE'); ?></strong></td>
                            <td><?php echo $order->BT->phone; ?></td>
                            <td><?php echo $order->ST->phone; ?></td>
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
                <th colspan="2"><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_PRODUCT_NAME'); ?></strong></th>
                <th><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_QUANTITY'); ?></strong></th>
                <th><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_UNITPRICE'); ?></strong></th>

            </tr>
        </thead>
        <tbody>
            <?php
foreach ($order->orderItems as $orderItem) {
        echo "<tr>";
        echo "<td colspan=\"2\" align=\"center\">$orderItem->order_item_name</td>";
        echo "<td align=\"center\">$orderItem->product_quantity db</td>";
        echo "<td align=\"center\">" . number_format(round($orderItem->product_item_price), 0, ',', ' ') . " Ft</td>";
        echo "</tr>";
    }
    ?>
            <tr class="payment-total">
                <td><strong>Részösszeg:</strong> <?php echo number_format(round($order->order_salesPrice), 0, ',', ' '); ?> Ft</td>
                <td><strong>Szállítási költség:</strong> <?php echo number_format(round($order->shipmentTotal), 0, ',', ' '); ?> Ft</td>
                <td><strong>Kuponjóváírás:</strong> <?php echo number_format(round($order->coupon_discount), 0, ',', ' '); ?> Ft</td>
                <td><strong>Összesen:</strong> <?php echo number_format(round($order->order_total), 0, ',', ' '); ?> Ft</td>
            </tr>
        </tbody>
    </table>

                <?php
if ($cnt != $orderCount) {
        echo "<div class=\"page-break-tag\"></div>";
    }
    $cnt = $cnt + 1;
    ?>

        </div>

    <?php } // foreach ($this->ordersToPrint as $order) ?>

<script>
    window.print();
</script>
