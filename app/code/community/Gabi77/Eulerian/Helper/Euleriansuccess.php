<?php
/**
 * @category   Gabi77
 * @package    Gabi77_Eulerian
 * @copyright  Copyright (c) 2013 Gabi77 (http://www.gabi77.com)
 * @author     Gabriel Janez <contact@gabi77.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * 
 */

/**
 * Gabi77 Eulerian Helper Euleriansuccess
 */
class Gabi77_Eulerian_Helper_Euleriansuccess extends Mage_Core_Helper_Abstract {
	
	/**
	 * Return the Id Customer
	 * 
	 * @return integer
	 */
	
	public function getCustomerId() {
		
		$customer	= Mage::getSingleton('customer/session')->getCustomer();
		$id			= $customer->getId();		// Id Client
		return $id;
	}
	/**
	 * Return the Email Customers
	 * 
	 * @return string
	 */
	public function getCustomerEmail() {
	
		$customer	= Mage::getSingleton('customer/session')->getCustomer();
		$email		= $customer->getEmail();	// Email Client
		return $email;
	}
	/**
	 * Return total order customers
	 * 
	 * @return number
	 */
	public function getCustomerTotalOrder() {
		$orders = Mage::getResourceModel('sales/order_collection')
					->addAttributeToSelect('*')
					->addAttributeToFilter('customer_id', self::getCustomerId())
					->addAttributeToSort('created_at', 'desc');
		// 			->addAttributeToFilter("state", Mage_Sales_Model_Order::STATE_COMPLETE)
		$return = $orders->count();
		
		return $return;
	}
	
}