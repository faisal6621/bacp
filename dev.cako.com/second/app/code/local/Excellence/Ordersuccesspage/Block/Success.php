<?php

class Excellence_Ordersuccesspage_Block_Success extends Mage_Checkout_Block_Onepage_Success {

    protected function _construct() {
        parent::_construct();
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order_info = Mage::getModel('sales/order')->load($orderId);
        Mage::register('current_order', $order_info);
        $this->setCanPrintOrder(true);
        $this->setCanViewOrder(true);
    }

    protected function _beforeToHtml() {

        parent::_beforeToHtml();
        $this->setTemplate('ordersuccesspage/success.phtml');
    }

}
