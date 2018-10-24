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

$order = $this->orderToView;

JToolbarHelper::title("Megrendelés (Megrendelés száma: $order->order_number)", 'csomagolo');
JToolBarHelper::back();

?>

<style>
    .tss-table {
        width: 95%;
    }

    td {
        padding: 9px 0;
    }

    .orderdets-history {
        empty-cells: hide;
    }

    .headerText {
        width: 30%;
        font-weight: bold;
    }

    .contentText {
        width: 35%;
    }

    .top-border {
        border-top: 1px solid #ddd;
    }

    .bottom-border {
        border-bottom: 1px solid #ddd;
    }

    .top-margin {
        margin-top: 30px;
    }

    /* History table */

    .history thead tr th {
        text-align: left;
    }

    /* Billing and payment addresses */
    .billing-address-header {
        width: 70%;
    }

    .billing-address-content {
        width: 30%;
    }

    .shipping-address-header {
        width: 50%;
    }

    .shipping-address-content {
        width: 50%;
    }

    /* Order items */
    .orderitem-table thead tr th {
        text-align: left;
    }
</style>

<!-- Order details and order history  -->
<table class="tss-table orderdets-history top-border">
    <tr>
        <td style="width: 30%; vertical-align: top;">
            <table class="orderdets" style="width: 100%;">
                <tr class="orderdets-row">
                    <td colspan="2" class="headerText"><strong>Webáruházi rendelés</strong></td>
                </tr>
                <tr class="orderdets-row top-border">
                    <td class="headerText">Megrendelés száma</td>
                    <td class="contentText" class="contentText">
                        <?php echo $order->order_number; ?>
                    </td>
                </tr>
                <tr class="orderdets-row top-border">
                    <td class="headerText">Titkos kulcs</td>
                    <td class="contentText">
                        <?php echo $order->order_pass; ?>
                    </td>
                </tr>
                <tr class="orderdets-row top-border">
                    <td class="headerText">Megrendelés dátuma</td>
                    <td class="contentText">
                        <?php echo $order->dateFormatted; ?>
                    </td>
                </tr>
                <tr class="orderdets-row top-border">
                    <td class="headerText">Megrendelés állapota</td>
                    <td class="contentText">
                        <?php echo JText::_($order->statusName); ?>
                    </td>
                </tr>
                <tr class="orderdets-row top-border">
                    <td class="headerText">Név</td>
                    <td class="contentText">
                        <?php echo $order->order_number; ?>
                    </td>
                </tr>
                <tr class="orderdets-row top-border">
                    <td class="headerText">IP-cím</td>
                    <td class="contentText">
                        <?php echo $order->ip_address; ?>
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 20%;"></td>
        <td style="width: 50%; vertical-align: top;">
            <table class="history tss-table">
                <thead>
                    <tr>
                        <th>Beérkezés dátuma</th>
                        <th style="padding-left: 20px;">Értesítsük a vásárlót?</th>
                        <th>Állapot</th>
                        <th>Kapcsolódó megjegyzések</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
foreach ($order->orderHistory as $historyEntry) {
    echo "<tr class=\"top-border\">";
    echo "<td>$historyEntry->dateFormatted</td>";
    echo "<td style=\"padding-left: 20px;\">$historyEntry->notifyCustomer</td>";
    echo "<td>$historyEntry->order_status_name</td>";
    echo "<td>$historyEntry->comments</td>";
    echo "</tr>";
}
?>
                </tbody>

            </table>
        </td>
    </tr>

</table> <!-- Order details and order history  -->

<!-- Billing and shipping methods -->
<table class="tss-table payment-shipment top-border top-margin">
    <tr>
        <td style="width: 35%; vertical-align: top;">
            <table style="width: 100%;">
                <tr>
                    <td colspan="2"><strong>Fizetés és szállítás</strong></td>
                </tr>
                <tr class="top-border">
                    <td>Fizetési mód</td>
                    <td>
                        <?php echo $order->paymentMethod; ?>
                    </td>
                </tr>
                <tr class="top-border">
                    <td>Szállítás</td>
                    <td>
                        <?php echo $order->shipmentMethod; ?>
                    </td>
                </tr>
                <tr class="top-border">
                    <td>Szállítási dátum</td>
                    <td>
                        <?php echo $order->delivery_date; ?>
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 75%;"></td>
    </tr>
</table> <!-- Billing and shipping methods -->

<!-- Billing and shipping addresses -->
<table class="tss-table billing-shipping-address top-margin">
    <thead>
        <tr class="bottom-border">
            <th style="padding: 9px 0;">Számlázási cím</th>
            <th style="padding: 9px 0;">Szállítási cím</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <table class="billing-address-table tss-table">
                    <tr>
                        <td class="billing-address-header">Megjegyzések és különleges kérések</td>
                        <td class="billing-address-content">
                            <?php echo $order->customerNote; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Megjegyzés a GLS futárnak</td>
                        <td class="billing-address-content">
                            <?php echo $order->glsNote; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Email</td>
                        <td class="billing-address-content">
                            <?php echo $order->BT->email; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Vezetéknév</td>
                        <td class="billing-address-content">
                            <?php echo $order->BT->firstName; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Keresztnév</td>
                        <td class="billing-address-content">
                            <?php echo $order->BT->lastName; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Utca, házszám</td>
                        <td class="billing-address-content">
                            <?php echo $order->BT->address; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Irányítószám</td>
                        <td class="billing-address-content">
                            <?php echo $order->BT->zip; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Város</td>
                        <td class="billing-address-content">
                            <?php echo $order->BT->city; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Ország</td>
                        <td class="billing-address-content">
                            <?php echo $order->BT->country; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Telefon</td>
                        <td class="billing-address-content">
                            <?php echo $order->BT->phone; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Kérjük segítsd a munkánkat. Milyen csatornán keresztül
                            jutottál el hozzánk?</td>
                        <td class="billing-address-content">
                            <?php echo $order->honnanHallott; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Adatkezelés</td>
                        <td class="billing-address-content">
                            <?php echo $order->adatkezeles; ?>
                        </td>
                    </tr>
                    <tr class="top-border">
                        <td class="billing-address-header">Hírlevél</td>
                        <td class="billing-address-content">
                            <?php echo $order->hirlevel; ?>
                        </td>
                    </tr>
                </table>
            </td>

            <td style="width: 50%; vertical-align: top;">
                <table class="shipping-address-table tss-table">
                    <tr>
                        <td class="shipping-address-header">Felhasználói címke a névhez</td>
                        <td class="shipping-address-content">
                            <?php echo $order->ST->username; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="shipping-address-header">Vezetéknév</td>
                        <td class="shipping-address-content">
                            <?php echo $order->ST->firstName; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="shipping-address-header">Keresztnév</td>
                        <td class="shipping-address-content">
                            <?php echo $order->ST->lastName; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="shipping-address-header">Utca, házszám</td>
                        <td class="shipping-address-content">
                            <?php echo $order->ST->address; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="shipping-address-header">Irányítószám</td>
                        <td class="shipping-address-content">
                            <?php echo $order->ST->zip; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="shipping-address-header">Város</td>
                        <td class="shipping-address-content">
                            <?php echo $order->ST->city; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="shipping-address-header">Ország</td>
                        <td class="shipping-address-content">
                            <?php echo $order->ST->country; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="shipping-address-header">Telefon</td>
                        <td class="shipping-address-content">
                            <?php echo $order->ST->phone; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table> <!-- Billing and shipping addresses -->

<!-- Order items table -->
<table class="tss-table top-margin orderitem-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Mennyiség</th>
            <th>Név</th>
            <th>Cikkszám</th>
            <th>A megrendelt termék állapota</th>
            <th>Termék ár (nettó)</th>
            <th>Alapár adóval együtt</th>
            <th>Termék ár (bruttó)</th>
            <th>ÁFA</th>
            <th>Kedvezmény</th>
            <th>Összesen</th>
        </tr>
    </thead>
    <tbody>
        <?php
for ($i = 1; $i <= 5; $i++) {
    echo "<tr>";
    echo "<td>$i</td>";
    echo "<td>Placeholder</td>";
    echo "<td>Placeholder</td>";
    echo "<td>Placeholder</td>";
    echo "<td>Placeholder</td>";
    echo "<td>Placeholder</td>";
    echo "<td>Placeholder</td>";
    echo "<td>Placeholder</td>";
    echo "<td>Placeholder</td>";
    echo "<td>Placeholder</td>";
    echo "<td>Placeholder</td>";
    echo "</tr>";
}
?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4"></td>
            <td style="text-align: right; padding-right: 10px;"><strong>Részösszeg:</strong></td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td style="text-align: right; padding-right: 10px;"><strong>Szállítási és kezelési költség:</strong></td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td style="text-align: right; padding-right: 10px;"><strong>Fizetési mód díja:</strong></td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td style="text-align: right; padding-right: 10px;"><strong>Összesen:</strong></td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
            <td>PH</td>
        </tr>
    </tfoot>
</table> <!-- Order items table -->

<!-- Shipping and payment details -->
<table class="tss-table billing-shipping-address top-margin">
    <thead>
        <tr>
            <th>Szállítás</th>
            <th>Fizetési mód</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <table class="tss-table">
                    <tr>
                        <td style="width: 35%; vertical-align: top;">Szállítási díj neve</td>
                        <td style="width: 65%; vertical-align: top;">Placeholder</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; vertical-align: top;">Megrendelés súlya</td>
                        <td style="width: 65%; vertical-align: top;">Placeholder</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; vertical-align: top;">Szállítási díj értéke</td>
                        <td style="width: 65%; vertical-align: top;">Placeholder</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; vertical-align: top;">Csomagolási díj</td>
                        <td style="width: 65%; vertical-align: top;">Placeholder</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; vertical-align: top;">Adó</td>
                        <td style="width: 65%; vertical-align: top;">Placeholder</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <table class="tss-table">
                    <tr>
                        <td style="width: 35%; vertical-align: top;">Név</td>
                        <td style="width: 65%; vertical-align: top;">Placeholder</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; vertical-align: top;">Teljes összeg a fizetés pénznemében</td>
                        <td style="width: 65%; vertical-align: top;">Placeholder</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; vertical-align: top;">Az email pénzneme</td>
                        <td style="width: 65%; vertical-align: top;">Placeholder</td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table> <!-- Shipping and payment details -->