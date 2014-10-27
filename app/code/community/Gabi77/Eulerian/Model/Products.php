<?php
/**
 * @category   Gabi77
 * @package    Gabi77_Eulerian
 * @copyright  Copyright (c) 2014 Gabi77 (http://www.gabi77.com)
 * @author     Gabriel Janez <contact@gabi77.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Gabi77_Eulerian_Model_Products extends Mage_Core_Model_Abstract {

	public function getProducts()
	{
		$products = Mage::getResourceModel('catalog/product_collection')
					->addAttributeToFilter('status',1)
					->addAttributeToFilter('visibility',array('neq'=>Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE))
					->addAttributeToFilter('price',array('neq'=>0))
					//->addAttributeToSelect('*')
					->addAttributeToSelect('name')
					->addAttributeToSelect('short_description')
					->addAttributeToSelect('special_price')
					->addAttributeToSelect('small_image')
					->addPriceData()
					->addStoreFilter();
		
	//	$products->setPageSize(10);

		return $products;
	}
	
	public function f($tagName,$content,$data=0)
	{
		$result = '<'.$tagName.'>';
		if($data) $result .= '<![CDATA[';
		$result .= trim(strip_tags($content));
		if($data) $result .= ']]>';
		$result .= '</'.$tagName.'>';
		return $result;
	}

	public function getQty($product,$qty=0)
	{
		//$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($id);
		$stockItem = $product->getStockItem();
		if($stockItem->getManageStock()) {
			if($stockItem->getIsInStock()) {
				$qty = intval($stockItem->getQty());
				$qty = ($qty <= 0) ? 0 : $qty;
			}
		} else {
			$qty = 100;
		}
		return $qty;
	}

	public function getIsStock($qty) {
		return ($qty > 0) ? Mage::helper('catalog')->__('In stock') : Mage::helper('catalog')->__('Out of stock');
	}
	
	public function getProductsCategories($product) {
		
// Multi root categories FIX
		$cats = $product->getCategoryCollection();	
		$result = array();				
		if(count($cats)) {
			foreach($cats as $category)
			{
				if($category->isInRootCategoryList())
				{
					$categories = $category->getParentCategories();
					foreach($categories as $c)
					{
						$result[$c->getLevel()] = $c->getName();
					}
					ksort($result);	
					break;
				}
			}
		}		
// Multi root categories FIX

		return $result;
	}
	
	public function getStore() { return Mage::app()->getStore(); }

}