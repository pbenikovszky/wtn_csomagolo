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

vmJsApi::css('kisker.default', 'administrator/components/com_virtuemart/assets/css/');
vmJsApi::css('tss.loader', 'administrator/components/com_virtuemart/assets/css/');

$sortByNameURL = "index.php?option=com_virtuemart&view=kisker&orderfunction=sortByNameAsc";
$sortByDateURL = "index.php?option=com_virtuemart&view=kisker&orderfunction=sortByDateDesc";
$sortByTotalURL = "index.php?option=com_virtuemart&view=kisker&orderfunction=sortByTotalDesc";

?>

<div id="loader" class="loader tss-hidden">
    <div class="loader-wrapper">
        <div class="lds-ripple"><div></div><div></div></div>
    </div>
</div>

<table id="kisker-table" class="kiskerTable">

    <thead valign="top">
        <!-- Buttons -->
        <tr class="kiserTable-buttonRow">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>
                <div id="btnChangeState" class="btn"><?php echo JText::_('COM_VIRTUEMART_KISKER_CHANGE_TO_KISKERFIZETETT'); ?></div>
            </th>
            <th></th>
            <th></th>
        </tr>

        <!-- Header labels -->
        <tr class="bottom-border">
            <th><div><?php echo JText::_('COM_VIRTUEMART_KISKER_TH1'); ?></div></th>

            <!-- Name column header set to link to sort by this column -->
            <?php if ($this->orderFunction == 'sortByNameAsc') {?>
                <th>
                    <div>
                        <a class="sort-link fontsize-130 active" href="<?php echo $sortByNameURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-up-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_KISKER_TH2'); ?>
                        </a>
                    </div>
                </th>
            <?php } else {?>
                <th>
                    <div>
                        <a class="sort-link fontsize-130" href="<?php echo $sortByNameURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-up-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_KISKER_TH2'); ?>
                        </a>
                    </div>
                </th>
            <?php }?>

            <th><div><?php echo JText::_('COM_VIRTUEMART_KISKER_TH3'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_KISKER_TH4'); ?></div></th>

            <!-- Name column header set to link to sort by this column -->
            <?php if ($this->orderFunction == 'sortByDateDesc') {?>
                <th>
                    <div>
                        <a class="sort-link active" href="<?php echo $sortByDateURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-down-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_KISKER_TH5'); ?>
                        </a>
                    </div>
                </th>
            <?php } else {?>
                <th>
                    <div>
                        <a class="sort-link" href="<?php echo $sortByDateURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-down-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_KISKER_TH5'); ?>
                        </a>
                    </div>
                </th>
            <?php }?>

            <th><div><?php echo JText::_('COM_VIRTUEMART_KISKER_TH6'); ?></div></th>
            <th><div><?php echo JText::_('COM_VIRTUEMART_KISKER_TH7'); ?></div></th>
            <th align="left"><div><?php echo JText::_('COM_VIRTUEMART_KISKER_TH8'); ?></div></th>
            <?php if ($this->orderFunction == 'sortByTotalDesc') {?>
                <th align="right">
                    <div>
                        <a class="sort-link active" href="<?php echo $sortByTotalURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-down-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_KISKER_TH9'); ?>
                        </a>
                    </div>
                </th>
            <?php } else {?>
                <th align="right">
                    <div>
                        <a class="sort-link" href="<?php echo $sortByTotalURL; ?>">
                            <img src="./components/com_virtuemart/assets/images/icon-arrow-down-b-128.png" alt="Print invoice" height="16" width="16">
                            <?php echo JText::_('COM_VIRTUEMART_KISKER_TH9'); ?>
                        </a>
                    </div>
                </th>
            <?php }?>
        </tr>
    </thead>

    <tbody>
<?php

foreach ($this->orders as $order) {

    if ($order->isKisker) {

        echo "<tr class=\"bottom-border";
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

        if ($order->isOverdue) {
            $styleClass = $styleClass . " overdue";
        }

        echo $styleClass . "\">";

        echo "<td align=\"center\"><input type=\"checkbox\" name=\"cbSelect\" value=\"$order->order_number\"></td>";

        echo "<td align=\"center\" class=\"fontsize-130\">" . $order->user_name . "</td>";

        echo "<td align=\"center\">" . $order->user_email . "</td>";

        echo "<td align=\"center\"><a href=\"index.php?option=com_virtuemart&view=csomagolo&task=vieworder&order_id=$order->virtuemart_order_id\">$order->order_number</a></td>";

        echo "<td align=\"center\">" . $order->dateFormatted . "</td>";

        echo "<td align=\"center\">" . $order->dateShippedFormatted . "</td>";

        echo "<td align=\"center\">" . $order->orderstate . "</td>";

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
vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/kisker.js', false, false);
?>

