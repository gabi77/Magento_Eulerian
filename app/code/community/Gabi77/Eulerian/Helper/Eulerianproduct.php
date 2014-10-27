<?php
/**
 * @category   Gabi77
 * @package    Gabi77_Eulerian
 * @copyright  Copyright (c) 2014 Gabi77 (http://www.gabi77.com)
 * @author     Gabriel Janez <contact@gabi77.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Gabi77 Eulerian Helper Eulerianproduct
 */
class Gabi77_Eulerian_Helper_Eulerianproduct extends Mage_Core_Helper_Abstract
{
    protected $_storeId = 0;

    public function setStoreId($storeId){$this->_storeId = $storeId;return $this;}

    public function getStoreId(){return $this->_storeId;return $this;}

	public function getEulerianCategoryProduct($_product)
	{
	/*** Récupération de toutes les catégories produit ****/
		
		 $categoriesinfo = $_product->getCategoryCollection();
		 $result = array();
		 if(count($categoriesinfo)) {
		 	foreach($categoriesinfo as $category)
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
		return $result;
	}

    /**
     * Get categories of product
     * @param Mage_Catalog_Model_Product $product
     * @return string $nodes
     */
    public function getCategories($_product, $url=0)
    {
        $nodes = "";
        $categoryCollection = $_product->getCategoryCollection()
            ->addAttributeToSelect(array('name'))
            ->addFieldToFilter('level',array('lteq'=>5))
            ->addUrlRewriteToResult()
            ->groupByAttribute('level')
            ->setOrder('level','ASC')
        ;

        $nbCategories = $categoryCollection->count();
        //echo "pId = ".$_product->getId()." nb = {$nbCategories}<br />";
        if($nbCategories < 1)
        {
            //Get categories from parent if exist
            $parentIds = Mage::getResourceSingleton('catalog/product_type_configurable')
                ->getParentIdsByChild($_product->getId());

            foreach ($parentIds as $parentId) {
                $categoryCollection = Mage::getResourceSingleton('catalog/product')
                    ->getCategoryCollection(new Varien_Object(array("id"=>$parentId)))
                    ->addAttributeToSelect(array('name'))
                    ->addFieldToFilter('level',array('lteq'=>5))
                    ->addUrlRewriteToResult()
                    ->groupByAttribute('level')
                    ->setOrder('level','ASC');

                if(($nbCategories = $categoryCollection->count()) > 0)
                    break;
            }
        }


        $cnt = 0;

        $sous = 'sous';
        foreach($categoryCollection as $category)
        {
            //$category->setStoreId($this->getStoreId()); //When store is setted the rewrite url don't found
            $category->getUrlInstance()->setStore($this->getStoreId());
            $category->getUrlInstance()->setUseSession(false);

            $name = $category->getName();
            $level = $category->getLevel();
            if($url == 1) {
                if($cnt == 0)
                {
                    $nodes .= '&prdp0k0=categorie&prdp0d0='.Mage::helper('gabi77_eulerian/eulerian')->getEulerianCleanText($name).'&';
                }
                else
                {
                    $nodes .= 'prdp0k'.$cnt.'='.$sous.'categorie&prdp0d'.$cnt.'='.Mage::helper('gabi77_eulerian/eulerian')->getEulerianCleanText($name).'&';
                    $sous .= 'sous';
                }
                $cnt++;
            } else {
                if($cnt == 0)
                {
                    $nodes .= "'prdparam-categorie','".Mage::helper('gabi77_eulerian/eulerian')->getEulerianCleanText($name)."',";
                }
                else
                {
                    $nodes .= "'prdparam-".$sous."categorie','".Mage::helper('gabi77_eulerian/eulerian')->getEulerianCleanText($name)."',";
                    $sous .= 'sous';
                }
                $cnt++;
            }


        }

        if($url == 1) {
            if($nbCategories == 0)
            {
                $nodes .= '&prdp0k0=categorie&prdp0d0=&';

                $cnt++;
            }

            for($i=($cnt);$i<=2;$i++)
            {
                $nodes .= 'prdp0k'.$cnt.'='.$sous.'categorie&prdp0d'.$cnt.'=&';
                $sous .= 'sous';

                $cnt++;
            }
        } else {
            if($nbCategories == 0)
            {
                $nodes .= "'prdparam-categorie','',";

                $cnt++;
            }

            for($i=($cnt);$i<=2;$i++)
            {
                $nodes .= "'prdparam-".$sous."categorie','',";
                $sous .= 'sous';
            }
        }
        $nodes = substr($nodes,0,-1);

        return $nodes;
    }
}