<?php

class Excellence_Storelocation_Model_Storeproduct extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('storelocation/storeproduct');
	}
}