<?php

class Excellence_Storelocation_Block_Adminhtml_Storelocation_Edit_Tab_Image extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	public $images;
	public function _construct() {
		parent::_construct();
		$this->setTemplate('storelocation/edit/tab/images.phtml');
	
	  $this->images = Mage::getModel('storelocation/storeimages')->getCollection()
                ->addFieldToFilter('storelocation_id', array('eq' => Mage::registry('storelocation_data')->getStorelocationId()));
		
	
	}
	public function getTabLabel() {
		return $this->__('Images');
	}

	public function getTabTitle() {
		return $this->__('Images');
	}

	public function canShowTab() {
		return true;
	}

	public function isHidden() {
		return false;
	}
	public function getValue() {
		return Mage::registry('storelocation_data')->getStoreimages1();
	}


	public function hasImages() {
        $imgs = $this->images;
//        $hasImage = empty($imgs->getData()) ? FALSE : TRUE;
        $hasImage = count($imgs->getData()) ? TRUE : FALSE;
        return $hasImage;
    }
	

  public function getImages() {
        return $this->images;
    }
    


}