<?php

class Excellence_Ordersuccesspage_OrdersuccesspageController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function emailAction() {
        $orderid = $this->getRequest()->getParam('order_id', false);
        if ($orderid) {
            $order = Mage::getModel('sales/order')->load($orderid);
            try {
                $order->sendNewOrderEmail();
            } catch (Exception $ex) {
                Mage::getSingleton('core/session')->addError($this->__($ex->getMessage()));
                $this->_redirect('customer/account/');
                return;
            }
            Mage::getSingleton('core/session')->addSuccess($this->__('Email sent successfully'));
        }
        $this->_redirect('customer/account/');
        return;
    }

}
