<?php

class Excellence_TipOrders_Adminhtml_OrdersController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('sales/tiporders')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    public function indexAction() {
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('tiporders/adminhtml_orders'));
        $this->renderLayout();
    }

    public function newAction() {
        $items = Mage::getModel("sales/order_item")->getCollection()
                ->addFieldToFilter("sku", '6001')
                ->getColumnValues('order_id');
        $collection = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter('entity_id', array('in' => $items));
        
    }

}
