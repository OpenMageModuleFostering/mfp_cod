<?php
class Mfp_Cod_Model_Observer {

	public function getCashOnDelvery(Varien_Event_Observer $observer) {
        $event           = $observer->getEvent();
        $method          = $event->getMethodInstance();
        $result          = $event->getResult();
        $isModuleEnable = Mage::getStoreConfig('cod/cod/enable');
		
		if($isModuleEnable) {
		
			if($method->getCode() == 'cashondelivery' ){
				
				$quote = Mage::getSingleton('checkout/cart')->getQuote();
				$add = $quote->getShippingAddress();
				$postcode = $add->getData('postcode');
				
				$comparisonMode = Mage::getStoreConfig('cod/cod/mode');
				$zipCodes = Mage::getStoreConfig('cod/cod/zipcode');
				$isExist = false;
				
				if(trim($zipCodes) == '') {				
					$result->isAvailable = true;
				} else {	
				
					if(strpos($zipCodes, $postcode) !==  false) {
						$isExist = true;
					}
						 
					
					if($isExist != true) {
						
						$zipCodesArray = explode(',', nl2br($zipCodes));
						if(count($elementLineArray) > 1) {
							foreach($zipCodesArray as $codzipLine) {
								$elementLineArray = explode('-', $codzipLine);
								if(count($elementLineArray) == 2 && ( $postcode >= $elementLineArray[0] && $postcode <= $elementLineArray[1] )) {
									$isExist = true;
									break;
								} else if($postcode == $codzipLine) {
									$isExist = true;
									break;
								}
							}
						}
					}
					
					$returnValue = '';
						$returnValue = ($isExist)?true:false;
						
					$result->isAvailable = $returnValue;
					
				}	
			
			} 
		}	
    }
}

