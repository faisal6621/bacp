<?php

class Excellence_TipOrders_Block_Adminhtml_Orders_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('ordersGrid');
        $this->setDefaultSort('real_order_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {

//        $collection = new Varien_Data_Collection();
        $items = Mage::getModel("sales/order_item")->getCollection()
                ->addFieldToFilter("sku", '6001')
                ->getColumnValues('order_id');
        /*
          foreach ($items as $item) {
          $order = Mage::getModel('sales/order')->load($item->getOrderId());
          $tmp[$item->getOrderId()] = $order;
          $obj = new Varien_Object();
          $obj->addData($tmp);
          $collection->addItem($obj);
          }
         */
        $collection = Mage::getResourceModel('sales/order_grid_collection')
                ->addFieldToFilter('entity_id', array('in' => $items));
        $collection->getSelect()
                ->joinLeft(
                        'cakosales_flat_order_address', 'main_table.entity_id = cakosales_flat_order_address.parent_id', array('telephone', 'city', 'postcode', 'email')
                )->where("cakosales_flat_order_address.address_type =  'billing'");
        $collection->getSelect()
                ->joinLeft(
                        array('odriver' => 'cakoorderdriver'), 'main_table.entity_id = odriver.orderid', array('driverid')
                )
                ->joinLeft(
                        array('ddriver' => 'cakodeliverydriver'), 'odriver.driverid = ddriver.deliverydriver_id', array('name')
        );
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('real_order_id', array(
            'header' => Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type' => 'text',
            'index' => 'increment_id',
        ));

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('email', array(
            'header' => Mage::helper('sales')->__('Email'),
            'index' => 'email',
            'filter_index' => 'cakosales_flat_order_address.email',
        ));
        $this->addColumn('ddriver', array(
            'header' => Mage::helper('ddate')->__('Delivery Driver'),
            'index' => 'name',
            'type' => 'options',
            'width' => '100px',
            'filter_index' => 'ddriver.name',
            'options' => Mage::getModel('deliverydriver/deliverydriver')->getDrivers(),
        ));
        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type' => 'currency',
            'currency' => 'order_currency_code',
        ));
    }

}
