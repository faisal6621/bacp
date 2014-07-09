<?php

class Excellence_Ordersuccesspage_Block_Checkout_Cart_ProductsBlock extends Mage_Core_Block_Template {

    protected $_blocks;

    public function __construct() {
        $collection = Mage::getModel('ordersuccesspage/cartProducts')->getCollection();
        $this->_blocks = $collection;
    }

    public function hasBlocks() {
        if ($this->_blocks->getSize() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public function getBlocks() {
        return $this->_blocks;
    }

}
