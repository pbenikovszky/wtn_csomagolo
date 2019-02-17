<?php
if (!defined('_JEXEC')) {
    die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
}

/**
 *
 * @version
 * @package VirtueMart
 * @subpackage Log
 * @copyright Copyright (C) VirtueMart Team - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.org
 */

if (!class_exists('VmController')) {
    require VMPATH_ADMIN . DS . 'helpers' . DS . 'vmcontroller.php';
}

/**
 * Report Controller
 *
 * @package    VirtueMart
 * @subpackage Report
 * @author Wicksj
 */
class VirtuemartControllerCsomagolo extends VmController
{

    /**
     * Log Controller Constructor
     */
    public function __constuct()
    {
        parent::__construct();
    }

    public function display()
    {
        $view = $this->getView('csomagolo', 'html');
        $view->setLayout('default');
        $view->orderFunction = JRequest::getVar('orderfunction');
        if ($view->orderFunction == '') {
            $view->orderFunction = 'sortByDateDesc';
        }
        $view->duplicated = (JRequest::getVar('duplicated') == "yes");
        $view->display();
    }

    // print one order
    public function printorder()
    {
        $view = $this->getView('csomagolo', 'html');
        $view->setLayout('printorder');
        $view->orderid = JRequest::getVar('orderid');
        $view->display();
    }

    // print multiple orders
    public function printorders()
    {
        $view = $this->getView('csomagolo', 'html');
        $view->setLayout('printorders');
        $view->orderNumbers = JRequest::getVar('ordernumbers');
        $view->display();
    }

    public function statechange()
    {
        $view = $this->getView('csomagolo', 'json');
        $view->setLayout('statechange');
        $view->orderNumbers = JRequest::getVar('ordernumbers');
        $view->newState = JRequest::getVar('newstate');
        $view->job = JRequest::getVar('job');
        $view->flagValue = JRequest::getVar('flagvalue');
        $view->display();
    }

    public function glsexport()
    {
        $view = $this->getView('csomagolo', 'json');
        $view->setLayout('glsexport');
        $view->display();
    }

    public function printinvoice()
    {
        $view = $this->getView('csomagolo', 'html');
        $view->setLayout('printinvoice');
        $view->invoiceNumber = JRequest::getVar('invoicenumber');
        $view->invoiceOrderID = JRequest::getVar('invoiceorderid');
        $view->display();
    }

    public function createinvoice()
    {
        $view = $this->getView('csomagolo', 'json');
        $view->setLayout('createinvoice');
        $view->invoiceOrderIDs = JRequest::getVar('invoiceorderids');
        $view->display();
    }

    public function vieworder()
    {
        $view = $this->getView('csomagolo', 'html');
        $view->setLayout('vieworder');
        $view->viewOrderID = JRequest::getVar('order_id');
        $view->display();
    }

    public function getconfirmedcount()
    {
        $view = $this->getView('csomagolo', 'json');
        $view->setLayout('getconfirmedcount');
        $view->display();
    }

    public function csomagolas()
    {
        $view = $this->getView('csomagolo', 'html');
        $view->setLayout('csomagolas');
        $view->display();
    }

    /**
     * Generic cancel task
     *
     */
    public function cancel()
    {
        // back from order
        $this->setRedirect('index.php?option=com_virtuemart&view=csomagolo');
    }

}
// pure php no closing tag
