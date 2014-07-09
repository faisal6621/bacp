<?php

class Excellence_Ordersuccesspage_Block_Adminhtml_CartProducts extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_cartProducts';
        $this->_blockGroup = 'ordersuccesspage';
        $this->_headerText = Mage::helper('ordersuccesspage')->__('Cart Products Block Manager');
        $this->_addButtonLabel = Mage::helper('ordersuccesspage')->__('Add Products Block');
        parent::__construct();
    }

}
