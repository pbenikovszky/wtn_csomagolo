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
defined ('_JEXEC') or die('Restricted access');

?>

<style>
    header,
    nav,
    .subhead-collapse {
        display: none !important;
    }

    table {
        font-family: 'Calibri', Arial, Helvetica, sans-serif;
        font-size: 12px;
        margin: 0 auto;
        width: 95%;
        border-collapse: collapse;
    }

    table,
    td,
    th {
        border: 1px solid black;
    }

    td,
    th {
        /* padding: 5px 0 5px 10px; */
    }


    /* Notes table */
    .notes-table {
        margin-top: 15px;
    }

    .note {
        min-height: 80px;
    }

    /* Address table */
    .address-table {
        margin-top: 15px;
    }

    .address-table>tbody {
        text-align: left;
    }

    /* Products table */
    .products-table {
        margin-top: 15px;
    }

    .products-table tbody tr :first-child {
        text-align: left;
    }
</style>

<div class="order-print-area">

    <!-- Rendelés adatai -->
    <table class="order-details-table">
        <col width="35%">
        <col width="65%">

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_NUMBER'); ?></strong></td>
            <td>
                <?php echo $this->orderDetails->order_number; ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_DATE'); ?></strong></td>
            <td>
                <?php echo $this->orderDetails->dateFormatted; ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_ORDER_STATE'); ?></strong></td>
            <td>
                <?php echo $this->orderDetails->statusName; ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_SHIPMENT_METHOD'); ?></strong></td>
            <td>
                <?php echo $this->orderDetails->shipmentMethod; ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_VENDOR'); ?></strong></td>
            <td>XY</td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUPON_CODE'); ?></strong></td>
            <td>
                <?php echo $this->orderDetails->coupon_code; ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_COUPON_DISCOUNT'); ?></strong></td>
            <td>……. Ft</td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_BANKACCOUNT'); ?></strong></td>
            <td>
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
            <td>
                <?php echo $this->orderDetails->paymentMethod; ?>
            </td>
        </tr>

        <tr>
            <td><strong>
                    <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_TOTALSUM'); ?></strong></td>
            <td>
                <?php echo number_format(round($this->orderDetails->order_total), 0, ',', ' '); ?> Ft</td>
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
                <th><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_GLS_NOTE'); ?></strong></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="note">
                        <?php echo $this->orderDetails->customerNote; ?>
                    </div>
                </td>
                <td>
                    <div class="note">
                        <?php echo $this->orderDetails->glsNote; ?>
                    </div>
                </td>
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
        <col width="80%">
        <col width="20%">
        <thead>
            <tr>
                <th><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_PRODUCT_NAME'); ?></strong></th>
                <th><strong>
                        <?php echo JText::_('COM_VIRTUEMART_PRINTVIEW_QUANTITY'); ?></strong></th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($this->orderDetails->orderItems as $orderItem) { 
                    echo "<tr>";
                    echo "<td align=\"center\">$orderItem->order_item_name</td>";
                    echo "<td align=\"center\">$orderItem->product_quantity db</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</div>


<script>
    window.print();
</script>