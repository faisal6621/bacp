<?php

try {
    include_once(Mage::getBaseDir('lib'). DS. 'SweetTooth'. DS .'SweetTooth.php');
} catch (Exception $e) {
    die(__FILE__ . ": Wasn't able to load lib/SweetTooth.php.  Download rewardsplatformsdk.git and run the installer to symlink it.");  
}


class TBT_Rewards_Model_Platform_Instance extends SweetTooth
{
    const CHANNEL_ID = 'magento';
    
    
    const CONFIG_API_KEY = 'rewards/platform/apikey';
    const CONFIG_SECRET_KEY = 'rewards/platform/secretkey';
    const CONFIG_API_URL = 'rewards/developer/apiurl';
    const CONFIG_API_SUBDOMAIN = 'rewards/platform/apisubdomain';
    const CONFIG_API_TIMEOUT = 'rewards/developer/api_timeout';

    public function __construct()  {

        $this->apiSubdomain = Mage::app()->getStore()->getConfig(self::CONFIG_API_SUBDOMAIN);
        $this->apiKey = Mage::app()->getStore()->getConfig(self::CONFIG_API_KEY);
    	$this->apiSecret = Mage::helper('core')->decrypt(Mage::app()->getStore()->getConfig(self::CONFIG_SECRET_KEY));

        $instance = parent::__construct($this->apiKey, $this->apiSecret, $this->apiSubdomain);
        $instance->setBaseDomain(Mage::getStoreConfig(self::CONFIG_API_URL));
        $instance->setTransferApiTimeout(Mage::getStoreConfig(self::CONFIG_API_TIMEOUT));

        return $instance;
    }
    
}
 