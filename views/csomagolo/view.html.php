<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage
 * @author
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists('VmViewAdmin')) {
    require VMPATH_ADMIN . DS . 'helpers' . DS . 'vmviewadmin.php';
}

/**
 * HTML View class for the VirtueMart Component
 *
 * @package        VirtueMart
 * @author
 */
class VirtuemartViewCsomagolo extends VmViewAdmin
{

    public function display($tpl = null)
    {

        // get the instance of our model
        $csomagoloModel = VmModel::getModel('csomagolo');

        // get the data from our model
        $this->orders = $csomagoloModel->getOrders();
        uasort($this->orders, array('VirtuemartViewCsomagolo', 'sortByName'));
        $this->orderstates = $csomagoloModel->getOrderstates();

        // get the parameters passed in the URL
        $this->orderDetails = $csomagoloModel->getOrderById($this->orderid);

        // for printorders view
        $this->orderNumberList = explode(",", $this->orderNumbers);
        $this->ordersToPrint = array();
        if (count($this->orderNumberList) > 0) {
            foreach ($this->orderNumberList as $ordernum) {
                $oID = $csomagoloModel->getIdFromNumber($ordernum);
                $currentOrder = $csomagoloModel->getOrderById($oID);
                array_push($this->ordersToPrint, $currentOrder);
            } // foreach
        } // if (count)

        // vieworder layout
        $this->orderToView = $csomagoloModel->getOrderById($this->viewOrderID);

        // create invoice layout

        // Set the toolbar
        JToolbarHelper::title(JText::_('COM_VIRTUEMART_TITLE'), 'csomagolo');

        parent::display($tpl);
    }

    private static function sortByName($a, $b)
    {
        $name1 = strtolower($a->user_name);
        $name2 = strtolower($b->user_name);
        return strcmp($name1, $name2);
    }

}
