<?php

class Excellence_TipOrders_Adminhtml_OrdersController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('report/tiporders')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    public function indexAction() {
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('tiporders/adminhtml_orders'));
        $this->renderLayout();
    }

    public function newAction() {
        $collection = Mage::getResourceModel('sales/order_grid_collection')
//                ->addFieldToFilter('entity_id', array('in' => $items))
        ;
        $collection->getSelect()
                ->joinLeft(
                        'cakosales_flat_order_address', 'main_table.entity_id = cakosales_flat_order_address.parent_id', array('telephone', 'city', 'postcode', 'email')
                )->where("cakosales_flat_order_address.address_type =  'billing'");
//        $collection->getSelect()
//                ->joinLeft(
//                        'cakosales_flat_order_item', 'main_table.entity_id = cakosales_flat_order_item.order_id', array('price', 'base_price', 'original_price')
//                )
//        ;
        $collection->getSelect()->
                joinLeft(array('sales_order_item' => 'cakosales_flat_order_item'), 'order_id = main_table.entity_id', array('tip_price' => 'price', 'item_sku' => 'sku'))
                ->where('sales_order_item.sku in (6001,790099)')
        ;
//        echo $collection->getSelect();
//        die;
        $collection->getSelect()
                ->joinLeft(
                        array('odriver' => 'cakoorderdriver'), 'main_table.entity_id = odriver.orderid', array('driverid')
                )
                ->joinLeft(
                        array('ddriver' => 'cakodeliverydriver'), 'odriver.driverid = ddriver.deliverydriver_id', array('name')
                )
        ;
        $collection->getSelect()->group('main_table.entity_id');
        $csvData = array();
        $csvData[] = array(
            'orderid' => 'Order #',
            'purchased' => 'Purchased On',
            'billto' => 'Bill to Name',
            'email' => 'Email',
            'driver' => 'Delivery Driver',
            'gt' => 'Grand Total',
            'sc' => 'Service Charge',
        );
        foreach ($collection as $order) {
            $csvData[] = array(
                'orderid' => $order->getIncrementId(),
                'purchased' => date('n/d/y', strtotime($order->getCreatedAt())),
                'billto' => $order->getBillingName(),
                'email' => $order->getEmail(),
                'driver' => $order->getName(),
                'gt' => $order->getGrandTotal(),
                'sc' => $order->getTipPrice(),
            );
        }
        $file = Mage::getBaseDir('media') . DS . 'service_charge.csv';
        try {
            $csv = new Varien_File_Csv();
            $csv->saveData($file, $csvData);
            $this->_redirectUrl(Mage::getBaseUrl('media') . 'service_charge.csv');
            return;
        } catch (Exception $ex) {
            $this->_getSession()->addError($this->__($ex->getMessage()));
        }
        $this->_redirectReferer();
        return;
    }

}
