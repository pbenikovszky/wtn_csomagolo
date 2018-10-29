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

?>

<style>

    header, nav, .subhead-collapse {
        display: none !important;
    }

    table {
        font-family: 'Calibri', Arial, Helvetica, sans-serif;
        font-size: 12px;
        margin: 0 auto;
        width: 95%;
        border-collapse: collapse;
    }

    table, td, th {
        border: 1px solid black;
    }

    td, th {
        /* padding: 5px 0 5px 10px; */
    }


    /* Notes table */
    .notes-table {
        margin-top: 15px;
    }

    .note {
        min-height: 120px;
    }

    /* Address table */
    .address-table {
        margin-top: 15px;
    }

    .address-table > tbody {
        text-align: left;
    }

    /* Products table */
    .products-table {
        margin-top: 15px;
    }

    .products-table tbody tr :first-child {
        text-align: left;
    }

    .page-break-tag {
        page-break-after: always;
    }


</style>

<?php
$orderCount = count($this->ordersToPrint);
$cnt = 1;
foreach ($this->ordersToPrint as $order) {?>

        <div class="order-print-area">

            <!-- Rendelés adatai -->
            <table class="order-details-table">
                <col width="35%">
                <col width="65%">

                <tr>
                    <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_NUMBER'); ?></strong></td>
                    <td><?php echo $order->order_number; ?></td>
                </tr>

                <tr>
                    <td><strong>
                            <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_ID'); ?></strong></td>
                    <td>
                    <?php echo $order->virtuemart_order_id; ?>
                    </td>
                </tr>

                <tr>
                    <td><strong>
                            <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_RETAIL'); ?></strong></td>
                    <td>
                    <?php echo ($order->isKisker) ? "Igen" : "Nem"; ?>
                    </td>
                </tr>

                <tr>
                    <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_DATE'); ?></strong></td>
                    <td><?php echo $order->dateFormatted; ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_STATE'); ?></strong></td>
                    <td><?php echo $order->statusName; ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_SHIPMENT_METHOD'); ?></strong></td>
                    <td><?php echo $order->shipmentMethod; ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_VENDOR'); ?></strong></td>
                    <td><?php echo $order->recommender; ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUPON_CODE'); ?></strong></td>
                    <td><?php echo $order->coupon_code; ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUPON_DISCOUNT'); ?></strong></td>
                    <td><?php echo number_format(round($order->coupon_discount), 0, ',', ' '); ?> Ft</td>
                </tr>

                <tr>
                    <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_BANKACCOUNT'); ?></strong></td>
                    <td>
                        <?php
if ($order->virtuemart_paymentmethod_id == 6) {
    echo $order->paymentDesc;
}
    ?>
                    </td>
                </tr>

                <tr>
                    <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_PAYMENT_METHOD'); ?></strong></td>
                    <td><?php echo $order->paymentMethod; ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_TOTALSUM'); ?></strong></td>
                    <td><?php echo number_format(round($order->order_total), 0, ',', ' '); ?> Ft</td>
                </tr>

                </table>

                <!-- Megjegyzések -->
                <table class="notes-table">
                    <col width="50%">
                    <col width="50%">
                    <thead>
                        <tr>
                            <th><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_CUSTOMER_NOTE'); ?></strong></th>
                            <th><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_GLS_NOTE'); ?></strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div class="note"><?php echo $order->customerNote; ?></div></td>
                            <td><div class="note"><?php echo $order->glsNote; ?></div></td>
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
                            <td><?php echo $order->BT->lastName; ?></td>
                            <td><?php echo $order->ST->lastName; ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_FIRSTNAME'); ?></strong></td>
                            <td><?php echo $order->BT->firstName; ?></td>
                            <td><?php echo $order->ST->firstName; ?></td>
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
                    <col width="80%">
                    <col width="20%">
                    <thead>
                        <tr>
                            <th><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_PRODUCT_NAME'); ?></strong></th>
                            <th><strong><?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_QUANTITY'); ?></strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
foreach ($order->orderItems as $orderItem) {
        echo "<tr>";
        echo "<td align=\"center\">$orderItem->order_item_name</td>";
        echo "<td align=\"center\">$orderItem->product_quantity db</td>";
        echo "</tr>";
    }
    ?>
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
