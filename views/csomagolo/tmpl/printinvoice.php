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

$csModel = VmModel::getModel('csomagolo');

$pdf = $csModel->getInvoicePDF($this->invoiceNumber, $this->invoiceOrderID);

if ($pdf->result == "SUCCESS") {
    echo "A $this->invoiceNumber számú számla sikeresen lekérdezve";
    ?>
    <script src="components/com_virtuemart/assets/js/print.min.js"></script>
    <link rel="stylesheet" type="text/css" href="components/com_virtuemart/assets/css/print.min.css">

    <script>
        let pdfName = '<?php echo $pdf->pdfFileName; ?>';
        let url = 'http://localhost/joomla/administrator/myInvoices/' + pdfName;
        // let url = 'http://masolat1.drbiroszabolcs.com/administrator/myInvoices/' + pdfName;
        printJS({
            printable: url,
            type: 'pdf',
            showModal: true
        });

    </script>

<?php
} else {
    echo "<h1>Hiba történt a számla lekérdezésekor.</h1>";
}
?>




