<?php
/**
 * @category   Gabi77
 * @package    Gabi77_Eulerian
 * @copyright  Copyright (c) 2013 Gabi77 (http://www.gabi77.com)
 * @author     Gabriel Janez <contact@gabi77.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * 
 */
class Gabi77_Eulerian_ExportController extends Mage_Core_Controller_Front_Action
{
	protected $_xml = '';
	
	public function xmlAction()
	{		
		$model = Mage::getModel('gabi77eulerian/products');
		
		$this->_xml = '<?xml version="1.0" encoding="utf-8"?><products>';
		
		foreach($model->getProducts() as $p)
		{			
			try
			{
				$this->_traiteXml($p,$model);
			}
			catch(Exception $e)
			{
				Mage::logException($e);	
			}
		}
		
		$this->_xml .= '</products>';

		return $this->getResponse()
					->setHttpResponseCode(200)
					->setHeader('Pragma', 'public', true)
					->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
					->setHeader('Content-type', 'text/xml', true)
					->setBody($this->_xml);
	}
	
	protected function _traiteXml($p,$model)
	{	
		$this->_xml .= '<product id="'.$p->getId().'">';
		$this->_xml .= $model->f('name', $p->getName(), 1);
		$this->_xml .= $model->f('smallimage', Mage::helper('catalog/image')->init($p, 'small_image')->resize(100), 0);
		$this->_xml .= $model->f('bigimage', Mage::helper('catalog/image')->init($p, 'small_image')->resize(500), 0);
		$this->_xml .= $model->f('producturl', $p->getProductUrl(), 0);
		$this->_xml .= $model->f('description', $p->getShortDescription(), 1);
		$pp = $p->getPrice();
		if($sp = $p->getSpecialPrice())
		{
			$this->_xml .= $model->f('price', round($sp*1.196,2), 0);
			$this->_xml .= $model->f('retailprice', round($pp*1.196,2), 0);
			$discount = round(($pp - $sp) / $pp * 100, 2);
			$this->_xml .= $model->f('discount', $discount, 0);
		}
		else
		{
			$this->_xml .= $model->f('price', round($pp*1.196,2), 0);
		}
		$this->_xml .= $model->f('recommendable', true, 0);
		$this->_xml .= $model->f('instock', $p->isSaleable(), 0);		
		$this->_xml .= '</product>';			
	}
	
	private function nf($v) { return number_format($v,2,'.',''); }
}