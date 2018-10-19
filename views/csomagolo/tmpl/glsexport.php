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

<h1>GLS export test view</h1>

<h3>Orders to export:</h3>
<p><?php echo $this->ordersToExport; ?></p>
<p><?php echo count($this->glsOrders); ?></p>

<table style="width: 95%">
    <thead>
        <th>Utánvét összege</th>
        <th>Címzett</th>
        <th>Ország</th>
        <th>Irszám</th>
        <th>Város</th>
        <th>Cím</th>
        <th>Telefon</th>
        <th>Email</th>
        <th>SMS</th>
        <th>Cégnév</th>
        <th>Utánvét hivatkozás</th>
        <th>Ügyfél hivatkozás</th>
        <th>Szolgáltatások</th>
        <th>Megjegyzés</th>
    </thead>
    <tbody>
        <?php
            foreach ($this->glsOrders as $order) { 
                echo "<tr>";
                for ($i = 0; $i<14; $i++) {
                    echo "<td align=\"center\">Ejha</td>";
                }
                echo "</tr>";
            }
        ?>
    </tbody>
</table>