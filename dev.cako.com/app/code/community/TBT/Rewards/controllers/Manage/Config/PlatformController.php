<?php

class TBT_Rewards_Manage_Config_PlatformController extends Mage_Adminhtml_Controller_Action
{
    public function disconnectAction()
    {
        Mage::getConfig()->saveConfig('rewards/platform/username', '');
        Mage::getConfig()->saveConfig('rewards/platform/email', '');
        Mage::getConfig()->saveConfig('rewards/platform/password', '');
        
        Mage::getConfig()->saveConfig('rewards/platform/apikey', '');
        Mage::getConfig()->saveConfig('rewards/platform/secretkey', '');
        Mage::getConfig()->saveConfig('rewards/platform/apisubdomain', '');

        Mage::getConfig()->saveConfig('rewards/platform/is_connected', 0);
        Mage::getConfig()->saveConfig('rewards/platform/is_signup', 0);
        Mage::getConfig()->saveConfig('rewards/platform/dev_mode', 1);
        
        $defaultApiUrl = (string)Mage::getConfig()->getNode('default/rewards/developer')->defaultapiurl;
        Mage::getConfig()->saveConfig('rewards/developer/apiurl', $defaultApiUrl);
        
        Mage::getConfig()->cleanCache();
        
        Mage::getSingleton('core/session')->addSuccess('Successfully disconnected your Sweet Tooth account.');
        
        $this->_redirect('adminhtml/system_config/edit', array('section' => 'rewards'));
        return $this;
    }
    
    public function connectAction()
    {
        Mage::getConfig()->saveConfig('rewards/platform/is_signup', 0);
        
        $username = $this->getRequest()->get('username');
        $password = $this->getRequest()->get('password');
        $isDevMode = $this->getRequest()->get('isDevMode');
        
        $defaultApiUrl = $isDevMode ?
            (string)Mage::getConfig()->getNode('default/rewards/developer')->defaultstagingurl :
            (string)Mage::getConfig()->getNode('default/rewards/developer')->defaultapiurl;
        Mage::getConfig()->saveConfig('rewards/developer/apiurl', $defaultApiUrl);
        Mage::getConfig()->cleanCache();
        
        Mage::helper('rewards/platform')->connectWithPlatformAccount($username, $password);
        
        Mage::getConfig()->saveConfig('rewards/platform/dev_mode', $isDevMode);
        
        $this->_redirect('adminhtml/system_config/edit', array('section' => 'rewards'));
        return $this;
    }
    
    public function signupAction()
    {
        Mage::getConfig()->saveConfig('rewards/platform/is_signup', 1);
        
        $username = $this->getRequest()->get('username');
        $email = $this->getRequest()->get('email');
        $password = $this->getRequest()->get('password');
        
        Mage::helper('rewards/platform')->createPlatformAccount($username, $email, $password);
        
        $this->_redirect('adminhtml/system_config/edit', array('section' => 'rewards'));
        return $this;
    }
   
}
