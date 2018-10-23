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

    .header-buttonTitle {
        min-height: 15px;
    }

    .header-container {
        min-height: 110px;
    }

    .tssBtn {
        min-width: 100px;
        margin-top: 8px;
    }

    /* Button width */

    .btnList {
        min-width: 80px !important;
    }

    .btnInvoice {
        min-width: 115px;
    }

    /* Table rowheight */

    .orderTable {

        width: 1850px;
        table-layout: fixed;

    }

    td {
        height: 40px;
    }

    .note-field {
        text-overflow: ellipse;
    }

    .db-state {
        width: 90%;
        margin: 0 auto;
    }

    .tss-hidden {
        display: none;
    }

    /* Style for the loader */

    .loader {
        position: absolute;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0,0,0,0.8);
        text-align: center;
    }

    .loader-wrapper {
        position: absolute;
        top: 30%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .lds-ripple {
        display: inline-block;
        position: relative;
        width: 64px;
        height: 64px;
    }

    .lds-ripple div {
        position: absolute;
        border: 4px solid #fff;
        opacity: 1;
        border-radius: 50%;
        animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
    }

    .lds-ripple div:nth-child(2) {
        animation-delay: -0.5s;
    }

    @keyframes lds-ripple {
        0% {
            top: 28px;
            left: 28px;
            width: 0;
            height: 0;
            opacity: 1;
        }
        100% {
            top: -1px;
            left: -1px;
            width: 58px;
            height: 58px;
            opacity: 0;
        }
    }
</style>

<div id="loader" class="loader tss-hidden">
    <div class="loader-wrapper">
        <div class="lds-ripple"><div></div><div></div></div>
    </div>
</div>

<!-- <h1><?php // echo JText::_('COM_VIRTUEMART_TITLE'); ?></h1>
<h3 style="margin-bottom: 20px;"><?php // echo JText::_('COM_VIRTUEMART_SUBTITLE'); ?></h3> -->

<table class="orderTable">
    <thead valign="top">
        <!-- Buttons -->
        <tr>
            <!-- Kijelölés -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER1'); ?></span></div>
                    <div id="btnSelectAll" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_SELECTALL'); ?></div>
                    <div id="btnDeselect" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_DESELECT'); ?></div>
                </div>
            </th>

            <!-- Megrendelés száma -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER2'); ?></span></div>
                </div>
            </th>

            <!-- Megrendelés dátuma -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER3'); ?></span></div>
                </div>
            </th>

            <!-- Kisker checkbox -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER4'); ?></span></div>
                    <div id="btnShowRetail" class="btn tssBtn btnList"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_SHOWRETAILONLY'); ?></div>
                    <div id="btnShowAll" class="btn tssBtn btnList"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_SHOWALL'); ?></div>
                </div>
            </th>

            <!-- Megrendelő neve -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER5'); ?></span></div>
                </div>
            </th>

            <!-- Megrendelő emailcíme -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER6'); ?></span></div>
                </div>
            </th>

            <!-- Számla / nyomtatás  -->
           <th colspan="2" style="width: 125px;">
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER7'); ?></span></div>
                    <div id="btnPrintAll" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_PRINTALL'); ?></div>
                </div>
            </th>

            <!-- Számla kiállítva -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER9'); ?></span></div>
                    <div id="btnIssueInvoice" class="btn tssBtn btnInvoice"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_ISSUEINVOICE'); ?></div>
                    <div id="btnPrintInvoice" class="btn tssBtn btnInvoice"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_PRINTINVOICE'); ?></div>
                </div>
            </th>

            <!-- Megrendelés állapota -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER10'); ?></span></div>
                    <div id="btnStateToGLS" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_STATETOGLS'); ?></div>
                    <div id="btnStateToDelivered" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_STATETODELIVERED'); ?></div>
                </div>
            </th>

            <!-- Megjegyzés -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER11'); ?></span></div>
                </div>
            </th>

            <!-- GLS megjegyzés -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER12'); ?></span></div>
                        <div id="btnGLSExport" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_GLSEXPORT'); ?></div>
                    </div>
            </th>

        </tr>

        <!-- Header labels -->
        <tr>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH1'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH2'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH3'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH4'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH5'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH6'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH7'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH8'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH9'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH10'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH11'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH12'); ?></div></th>
        </tr>
    </thead>
    <tbody>
        <?php
$cnt = 0;
foreach ($this->orders as $order) {
    $cnt += 1;
    //echo "<tr class=\"tss-table-row\" data-invoicenumber=\"$order->invoiceNumber\" data-invoiceurl=\"$order->invoiceURL\">";
    echo "<tr class=\"tss-table-row\">";
    // Kijelölés
    echo "<td align=\"center\"><input type=\"checkbox\" name=\"cbSelect\" value=\"$order->order_number\"></td>";

    // Megrendelésszám
    echo "<td align=\"center\"><a href=\"index.php?option=com_virtuemart&view=csomagolo&task=vieworder&order_id=$order->virtuemart_order_id\">$order->order_number</a></td>";

    // Megrendelés dátuma
    echo "<td align=\"center\">$order->dateFormatted</td>";
    // echo "<td align=\"center\">$order->created_on</td>";

    // Kisker-e checkbox
    echo "<td align=\"center\"><input type=\"checkbox\" name=\"cbKisker$cnt\" disabled=\"disabled\" value=\"$order->order_number\"";
    echo ($order->isKisker == true) ? " checked></td>" : "></td>";

    // Megrendelő neve
    echo "<td align=\"center\">$order->user_name</td>";

    // Megrendelő emailcíme
    echo "<td align=\"center\">$order->user_email</td>";

    // Előnézet
    echo "<td style=\"width:60px\" align=\"center\">
                        <a href=\"javascript:void window.open('index.php?option=com_virtuemart&view=csomagolo&task=printinvoice&invoicenumber=$order->invoiceNumber&invoiceorderid=$order->virtuemart_order_id', '_blank');\">
                            <img src=\"./components/com_virtuemart/assets/images/icon_32/invoicenew.png\" alt=\"Smiley face\" height=\"32\" width=\"32\">
                        </a></td>";

    // Nyomtatás
    echo "<td style=\"width:60px\" align=\"center\">
                        <a href=\"javascript:void window.open('index.php?option=com_virtuemart&view=csomagolo&task=printorder&orderid=$order->virtuemart_order_id', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\">
                            <img src=\"./components/com_virtuemart/assets/images/icon_32/printer.png\" alt=\"Smiley face\" height=\"32\" width=\"32\">
                        </a></td>";

    // echo "<td align=\"center\" colspan=\"2\">
    // <a href=\"javascript:void window.open('index.php?option=com_virtuemart&view=csomagolo&task=printorder&orderid=$order->virtuemart_order_id', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\">
    //     <img src=\"./components/com_virtuemart/assets/images/icon_32/printer.png\" alt=\"Smiley face\" height=\"32\" width=\"32\">
    // </a></td>";

    // Számla kiállítva
    echo "<td align=\"center\"><input type=\"checkbox\" name=\"cbInvoice$cnt\" disabled=\"disabled\" value=\"$order->order_number\"";
    echo ($order->hasInvoice) ? " checked></td>" : "></td>";

    // Megrendelés állapota
    echo "<td><select align=\"center\" class=\"db-state\">";
    foreach ($this->orderstates as $orderstate => $state_value) {
        if ($state_value == $order->order_status) {
            echo "<option value=\"$state_value\" selected=\"selected\">$orderstate</option>";
        } else {
            echo "<option value=\"$state_value\">$orderstate</option>";
        }
    }
    echo "</select></td>";

    // Megjegyzés
    echo "<td class=\"note-field\" align=\"center\">$order->comment</td>";

    // GLS megjegyzés
    echo "<td class=\"note-field\" align=\"center\">$order->gls_note</td>";

    echo "</tr>";
}
?>
    </tbody>
</table>

<?php
vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/csomagolo.js', false, false);
?>

<script>

</script>