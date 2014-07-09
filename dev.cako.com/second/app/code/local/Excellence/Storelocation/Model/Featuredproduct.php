<?php

class Excellence_Storelocation_Model_Featuredproduct extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('storelocation/featuredproduct');
	}

	public function getProduct()
	{

		$collection = Mage::getModel('catalog/product')
		->getCollection()
		->addAttributeToFilter('visibility', 4)
		->addAttributeToSelect('*');

		$product=array();
		foreach($collection as $v)
		{
			$product[$v->getId()]=$v->getName();
		}
		return $product;
	}
}