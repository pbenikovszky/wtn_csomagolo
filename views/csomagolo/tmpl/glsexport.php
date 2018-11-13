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

if ($this->exportCSV === "NA") {
    echo "Nincs listázásra váró rendelés! Statusza: GLS csomagfeladásra vár!";
} else {

    if ((!isset($_SESSION['tss_vik_csinal']))) {
        $_SESSION['tss_vik_csinal'] = 0;
    }

    $_SESSION['tss_vik_csinal'] = 1 - $_SESSION['tss_vik_csinal'];

    $fnev[0] = "fj";
    $fnev[1] = "excel";

    $fileName = $fnev[$_SESSION['tss_vik_csinal']] . date('Y-m-d') . ".csv";

    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');

    echo $this->exportCSV;
}
