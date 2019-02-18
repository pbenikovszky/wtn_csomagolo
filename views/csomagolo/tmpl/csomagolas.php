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

vmJsApi::css('csomagolo.default', 'administrator/components/com_virtuemart/assets/css/');
$bar = &JToolBar::getInstance('toolbar');
$bar->appendButton('Link', 'checkmark-circle', 'Megerősített megrendelések', 'index.php?option=com_virtuemart&view=csomagolo');
$bar->appendButton('Separator');
$bar->appendButton('Link', 'cube', 'Megrendelések csomagolásra', 'index.php?option=com_virtuemart&view=csomagolo&task=csomagolas');

$sortByNameURL = "index.php?option=com_virtuemart&view=csomagolo&orderfunction=sortByNameAsc";
$sortByDateURL = "index.php?option=com_virtuemart&view=csomagolo&orderfunction=sortByDateDesc";
$sortByTotalURL = "index.php?option=com_virtuemart&view=csomagolo&orderfunction=sortByTotalDesc";

if ($this->duplicated) {
    $sortByNameURL .= "&duplicated=yes";
    $sortByDateURL .= "&duplicated=yes";
    $sortByTotalURL .= "&duplicated=yes";
}

?>

<div id="loader" class="loader tss-hidden">
    <div class="loader-wrapper">
        <div class="lds-ripple"><div></div><div></div></div>
    </div>
</div>

<div class="counters">
    <span class="counter">Megerősített rendelések: <span id="confirmed-counter"><?php echo $this->counters->countConfirmed; ?></span> db.</span>
    <span class="counter">Csomagolás rendelések: <?php echo $this->counters->countPackage; ?> db.</span>
    <span class="counter">GLS csomagfeladásra váró rendelések: <?php echo $this->counters->countGLS; ?> db.</span>
    <span class="counter">Várakoztatott rendelések: <?php echo $this->counters->countPending; ?> db.</span>
</div>

<div class="last-updated-wrapper">
    <span class="last-updated-text">(Utoljára frissítve: <span id="last-updated-time"></span>)</span>
</div>

<table id="order-table" class="orderTable" data-duplicated="<?php echo ($this->duplicated) ? '1' : '0'; ?>" >

    <thead valign="top">
        <!-- Buttons -->
        <tr>
            <!-- Kijelölés -->
            <th style="width: 126px;">
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER1'); ?></span></div>
                    <div id="btnSelectAll" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_SELECTALL'); ?></div>
                    <div id="btnDeselect" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_DESELECT'); ?></div>
                </div>
            </th>

            <!-- Megrendelő neve -->
            <th>

            </th>

            <!-- Megrendelő emailcíme -->
            <th>
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER5'); ?></span></div>
                </div>
            </th>

            <!-- Kisker checkbox -->
            <th style="width: 107px;">
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER4'); ?></span></div>
                    <div id="btnShowRetail" class="btn tssBtn btnList"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_SHOWRETAILONLY'); ?></div>
                    <div id="btnShowAll" class="btn tssBtn btnList"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_SHOWALL'); ?></div>
                </div>
            </th>

            <!-- Megrendelés száma -->
            <th style="width: 112px;">
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER2'); ?></span></div>
                </div>
            </th>

            <!-- Megrendelés dátuma -->
            <th style="width: 120px;">
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER3'); ?></span></div>
                </div>
            </th>

            <!-- Számlanyomtás / Megrendelésnyomtatás  -->
           <th colspan="2" style="width: 130px;">
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER7'); ?></span></div>
                    <div id="btnPrintAll" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_PRINTALL'); ?></div>
                </div>
            </th>

            <!-- Számla kiállítva -->
            <th style="width: 141px;">
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER9'); ?></span></div>
                    <div id="btnDuplicated" class="btn tssBtn btnInvoice"><?php
if ($this->duplicated) {
    echo JText::_('COM_VIRTUEMART_PACKAGE_DUPLICATED_SHOWALL');
} else {
    echo JText::_('COM_VIRTUEMART_PACKAGE_DUPLICATED');
}
?></div>
                    <div id="btnIssueInvoice" class="btn tssBtn btnInvoice"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_ISSUEINVOICE'); ?></div>
                </div>
            </th>

            <!-- Megrendelés állapota -->
            <th style="width: 126px;">
                <div class="header-container">
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER10'); ?></span></div>
                    <div id="btnStateToGLS" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_STATETOGLS'); ?></div>
                    <div id="btnStateToShipped" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_STATETOSHIPPED'); ?></div>
                </div>
            </th>

            <!-- Megjegyzés -->
            <th align="left" style="width: 350px;">
                <div class="header-container">
                    <!-- <div class="header-buttonTitle"><span><?php // echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER11'); ?></span></div> -->
                    <div class="header-buttonTitle"><span><?php echo JText::_('COM_VIRTUEMART_PACKAGE_HEADER12'); ?></span></div>
                        <div id="btnGLSExport" class="btn tssBtn"><?php echo JText::_('COM_VIRTUEMART_PACKAGE_GLSEXPORT'); ?></div>
                    </div>
                </div>
            </th>

            <!-- Rendelés összege -->
            <th style="width: 95px;">
                <div class="header-container">
                </div>
            </th>

        </tr>

        <!-- Header labels -->
        <tr class="bottom-border">
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH1'); ?></div></th>

            <!-- Name column header set to link to sort by this column -->
            <?php if ($this->orderFunction == 'sortByNameAsc') {?>
                <th>
                    <div>
                        <a class="sort-link fontsize-130 active" href="<?php echo $sortByNameURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-up-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH5'); ?>
                        </a>
                    </div>
                </th>
            <?php } else {?>
                <th>
                    <div>
                        <a class="sort-link fontsize-130" href="<?php echo $sortByNameURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-up-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH5'); ?>
                        </a>
                    </div>
                </th>
            <?php }?>

            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH6'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH4'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH2'); ?></div></th>

            <!-- Name column header set to link to sort by this column -->
            <?php if ($this->orderFunction == 'sortByDateDesc') {?>
                <th>
                    <div>
                        <a class="sort-link active" href="<?php echo $sortByDateURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-down-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH3'); ?>
                        </a>
                    </div>
                </th>
            <?php } else {?>
                <th>
                    <div>
                        <a class="sort-link" href="<?php echo $sortByDateURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-down-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH3'); ?>
                        </a>
                    </div>
                </th>
            <?php }?>

            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH7'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH8'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH9'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH10'); ?></div></th>
            <th align="left"><div><?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH11'); ?></div></th>
            <?php if ($this->orderFunction == 'sortByTotalDesc') {?>
                <th align="right">
                    <div>
                        <a class="sort-link active" href="<?php echo $sortByTotalURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-down-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH12'); ?>
                        </a>
                    </div>
                </th>
            <?php } else {?>
                <th align="right">
                    <div>
                        <a class="sort-link" href="<?php echo $sortByTotalURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-down-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_PACKAGE_TH12'); ?>
                        </a>
                    </div>
                </th>
            <?php }?>
            <!-- <th align="right"><div><?php //echo JText::_('COM_VIRTUEMART_PACKAGE_TH12'); ?></div></th> -->
        </tr>
    </thead>
    <tbody>
        <?php
$cnt = 0;
foreach ($this->orders as $order) {

    if ($order->order_status != 'C') {

        if ($this->duplicated == true && $order->isDuplicated == false) {
            continue;
        }

        $cnt += 1;
        echo "<tr class=\"tss-table-row bottom-border side-border";
        $styleClass = "";

        if ($order->isKisker) {
            $styleClass = " retail";
        }

        if ($order->isRecommended) {
            $styleClass = " recommended";
        }

        if ($order->isCouponUsed) {
            $styleClass = " coupon";
        }

        if ($order->order_status === "V") {
            $styleClass = " pending";
        }

        echo $styleClass . "\" data-orderid=\"$order->virtuemart_order_id\" data-invoice=";
        echo ($order->hasInvoice) ? "\"1\" data-manualinvoice=" : "\"0\" data-manualinvoice=";
        echo ($order->manualInvoice) ? "\"1\">" : "\"0\">";

        // Kijelölés
        echo "<td align=\"center\"><input type=\"checkbox\" name=\"cbSelect\" value=\"$order->order_number\"></td>";

        // Megrendelő neve
        echo "<td class=\"fontsize-130";
        if ($order->isDuplicated) {
            echo " duplicated";
        }
        echo "\" align=\"center\">";
        echo $order->user_name;
        if ($order->isRecommended) {
            echo "<br>";
            echo "Partner: " . $order->recommender;
        }

        echo "</td>";

        // Megrendelő emailcíme
        echo "<td align=\"center\">";
        echo $order->user_email;
        if ($order->isCouponUsed) {
            echo "<br>";
            echo "Kuponkód: " . $order->coupon_code;
        }
        echo "</td>";

        // Kisker-e checkbox
        echo "<td align=\"center\"><input type=\"checkbox\" name=\"cbKisker$cnt\" disabled=\"disabled\" value=\"$order->order_number\"";
        echo ($order->isKisker == true) ? " checked></td>" : "></td>";

        // Megrendelésszám
        echo "<td align=\"center\"><a href=\"index.php?option=com_virtuemart&view=csomagolo&task=vieworder&order_id=$order->virtuemart_order_id\">$order->order_number</a></td>";

        // Megrendelés dátuma
        echo "<td align=\"center\">$order->dateFormatted</td>";

        // Számla nyomtatás
        if ($order->hasInvoice) {
            echo "<td style=\"width:60px\" align=\"center\">
                        <a href=\"javascript:void window.open('index.php?option=com_virtuemart&view=csomagolo&task=printinvoice&invoicenumber=$order->invoiceNumber&invoiceorderid=$order->virtuemart_order_id', '_blank');\">
                            <img src=\"./components/com_virtuemart/assets/images/icon_32/invoicenew.png\" alt=\"Print invoice\" height=\"32\" width=\"32\">
                        </a></td>";
        } else {
            echo "<td style=\"width:60px\" align=\"center\"></td>";
        }

        // Nyomtatás
        if ($order->order_status == "B") {
            echo "<td style=\"width:60px\" align=\"center\">
                        <a href=\"javascript:void window.open('index.php?option=com_virtuemart&view=csomagolo&task=printorder&orderid=$order->virtuemart_order_id', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\">
                            <img src=\"./components/com_virtuemart/assets/images/icon_32/printer.png\" alt=\"Smiley face\" height=\"32\" width=\"32\">
                        </a></td>";
        } else {
            echo "<td style=\"width:60px\" align=\"center\"></td>";
        }
        // Manuális számlázás
        echo "<td align=\"center\"><input type=\"checkbox\" name=\"cbManualInvoice\" value=\"$order->order_number\"";
        echo ($order->manualInvoice == true) ? " checked></td>" : "></td>";

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
        echo "<td class=\"note-field\" align=\"left\">$order->comment</td>";

        // Végösszeg
        echo "<td class=\"note-field\" align=\"right\">$order->orderTotal</td>";

        echo "</tr>";
    }
}
?>
    </tbody>
</table>

<?php

vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/csomagolo_sharedsubs.js', false, false);
vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/csomagolo_csomagolas.js', false, false);
vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/csomagolo_updater.js', false, false);
?>

<script>

</script>