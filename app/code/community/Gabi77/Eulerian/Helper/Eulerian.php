<?php
/**
 * @category   Gabi77
 * @package    Gabi77_Eulerian
 * @copyright  Copyright (c) 2014 Gabi77 (http://www.gabi77.com)
 * @author     Gabriel Janez <contact@gabi77.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Gabi77 Eulerian Helper
 */
class Gabi77_Eulerian_Helper_Eulerian extends Mage_Core_Helper_Abstract
{
    
    public function getEulerianUrl()
    {
        $url = Mage::getStoreConfig('eulerian/tageulerian/href');
        return $url;
    }
    
    public function getEulerianCleanText($chaine)
    {
    $array_in	=	array('é','è','ê','ë','à','â','ù','ü','û','ô','ï','î','ç');
    $array_out	=	array('e','e','e','e','a','a','u','u','u','u','i','i','c');
	$chaine = str_replace($array_in, $array_out, $chaine);
	$chaine = str_replace(" ", "_", $chaine);
	$chaine = strtolower($chaine);
	return $chaine;
    }
    
    public function getEulerianIframe($param = NULL)
    {
    	$uniqueid = sha1(date("YmdHis"));    	
    	$result = '<noscript> 
			<iframe width="2" height="2" border="0" src="//'.self::getEulerianUrl().'/collector/-/'.$uniqueid.'.html?'.$param.'&url='.urlencode(Mage::getBaseUrl(Mage_Core_Model_Store:: URL_TYPE_WEB)).'"></iframe>
		      </noscript>	';
    	return $result;
    }
    
}