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

if ((!isset($_SESSION['vik_csinal']))) $_SESSION['vik_csinal']=0;
$_SESSION['vik_csinal'] = 1 - $_SESSION['vik_csinal'];

echo "vik_csinal: " . $_SESSION['vik_csinal'] . "\n";
echo "test: \n" . $this->test;


