<?php
class FW_LinkShare_Helper_Data extends Mage_Core_Helper_Abstract 
{
	/**
     * Config path for using throughout the code
	 * @var string $XML_PATH
     */
    const XML_PATH = 'thirdparty/linkshare/';
	
	/**
	 * Whether Link Share is enabled
	 *
	 * @param mixed $store
	 * @return bool
	 */
	public function isLinkShareEnabled($store = null)
	{
		return Mage::getStoreConfig('thirdparty/linkshare/active', $store);
	}

	/**
	 * Get the Link Share Merchant ID
	 *
	 * @param mixed $store
	 * @return string
	 */
	public function getMerchantID($store = null)
	{
		return Mage::getStoreConfig('thirdparty/linkshare/merchantid', $store);
	}

	/**
	 * Whether Link Share is ready to use
	 *
	 * @param mixed $store
	 * @return bool
	 */
	public function isLinkShareAvailable($store = null)
	{
		$enabled = $this->isLinkShareEnabled($store);
		$merchantid = $this->getMerchantID($store);
		
		if($enabled == 1 && $merchantid != NULL){
			return true;
		}else{
			return false;
		}
	}
}
