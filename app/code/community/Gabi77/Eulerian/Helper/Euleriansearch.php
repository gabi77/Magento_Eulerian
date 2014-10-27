<?php
/**
 * @category   Gabi77
 * @package    Gabi77_Eulerian
 * @copyright  Copyright (c) 2014 Gabi77 (http://www.gabi77.com)
 * @author     Gabriel Janez <contact@gabi77.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Gabi77 Euleriansearch Helper
 */
class Gabi77_Eulerian_Helper_Euleriansearch extends Mage_Core_Helper_Abstract
{
    
    public function getSearch()
    {
    	$queryText = Mage::helper('catalogSearch')->getQueryText();
    	$querymodel = Mage::getModel('catalogsearch/query');
    	$querymodel->loadByQueryText($queryText);
    	$result = $querymodel->getData();
    	return $result;
    }
}