<?php
class Excellence_Posimg_Block_Posimg extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getPosimg()     
     { 
        if (!$this->hasData('posimg')) {
            $this->setData('posimg', Mage::registry('posimg'));
        }
        return $this->getData('posimg');
        
    }
}