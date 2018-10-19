<?php
if( !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

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

if(!class_exists('VmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmcontroller.php');


/**
 * Report Controller
 *
 * @package	VirtueMart
 * @subpackage Report
 * @author Wicksj
 */
class VirtuemartControllerCsomagolo extends VmController {

	/**
	 * Log Controller Constructor
	 */
	function __constuct(){
		parent::__construct();

	}

	// print one order
	public function printorder() {
		$view = $this->getView( 'csomagolo', 'html' );
		$view->setLayout('printorder');
		$view->orderid = JRequest::getVar('orderid');
		$view->display();
	}

	public function printorders() {
		$view = $this->getView( 'csomagolo', 'html' );
		$view->setLayout('printorders');
		$view->orderNumbers = JRequest::getVar('ordernumbers');
		$view->display();
	}

	// print multiple orders



	public function statechange() {
		$view = $this->getView( 'csomagolo', 'json' );
		$view->setLayout('statechange');
		$view->orderNumbers = JRequest::getVar('ordernumbers');
		$view->newState = JRequest::getVar('newstate');
		$view->display();
	}

	public function glsexport() {
		// ! HTML format is only for the development phase
		// ! needs to be changed to json once done
		$view = $this->getView( 'csomagolo', 'html' );
		$view->setLayout('glsexport');
		$view->ordersToExport = JRequest::getVar('glsorders');
		$view->display();
	}

	/**
	 * Generic cancel task
	 *
	 */
	public function cancel() {
		// back from order
		$this->setRedirect('index.php?option=com_virtuemart&view=csomagolo' );
	}

}
// pure php no closing tag