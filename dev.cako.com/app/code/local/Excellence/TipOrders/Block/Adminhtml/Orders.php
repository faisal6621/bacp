<?php

class Excellence_TipOrders_Block_Adminhtml_Orders extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_orders';
        $this->_blockGroup = 'tiporders';
        $this->_headerText = Mage::helper('tiporders')->__('Orders with Service Charge');
        $this->_addButtonLabel = Mage::helper('tiporders')->__('Export Data');
        parent::__construct();
//        $this->_removeButton('add');
    }

}
