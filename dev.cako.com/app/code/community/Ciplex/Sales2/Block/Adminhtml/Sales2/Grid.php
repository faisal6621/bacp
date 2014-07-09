<?php

//class Ciplex_Sales2_Block_Adminhtml_Sales2_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
class Ciplex_Sales2_Block_Adminhtml_Sales2_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

	public function __construct()
	{
		parent::__construct();
		$this->setId('sales2_order_grid');
		$this->setUseAjax(true);
		$this->setDefaultSort('additional.shipping_date');
		$this->setDefaultDir('asc');
		$this->setSaveParametersInSession(true);
	}

	protected function _getCollectionClass()
	{
		return 'sales/order_grid_collection';
	}
	protected function _prepareCollection()
	{
		$ddate = Mage::getModel('ddate/dtime')->getCollection();
		$collection = Mage::getResourceModel($this->_getCollectionClass());
		$collection->getSelect()
		->joinLeft(
		array('ddate_store'=>$ddate->getTable('ddate_store')),
	        	'ddate_store.increment_id = main_table.increment_id',
		array('ddate_store.ddate_id')
		)
		->joinLeft(
		array('mwddate'=>$ddate->getTable('ddate')),
	        	'mwddate.ddate_id = ddate_store.ddate_id',
		array('mwddate.ddate')
		)
		->joinLeft(
		array('mwdtime' => $ddate->getTable('dtime')),
            'mwdtime.dtime_id = mwddate.dtime',
		array('mwdtime.dtime')
		)
		->joinLeft(
		array('order' => 'cakosales_flat_order'),
            'main_table.entity_id = order.entity_id',
		array('shipping_description','state')
		)
		->joinLeft(
		array('additional' => 'cakoorders_additional'),
            'main_table.entity_id = additional.order_id',
		array('fulfilled_by', 'shipping_date', 'shipping_time', 'custom_order','shipping_time2')
		)
		->joinLeft(
		array('odriver' => 'cakoorderdriver'),
            'main_table.entity_id = odriver.orderid',
		array('driverid')
		)
		->joinLeft(
		array('ddriver' => 'cakodeliverydriver'),
            'odriver.driverid = ddriver.deliverydriver_id',
		array('name')
		)
		->joinLeft(
		array('pp' => 'cakoorderpickup'),
            'pp.order_id = order.entity_id',
		array('order_pickup','delivery_loc')
		)
		;

		;
		$today = date('Y-m-d');
		$collection->getSelect()->where('`order`.`created_at` like \''.$today.'%\' or `order`.`state` not in (\'complete\',\'canceled\',\'closed\') ');
		$collection->getSelect()->order('additional.shipping_date asc');
		$collection->getSelect()->order('additional.shipping_time2 asc');

		// $collection->printLogQuery(true); die();
		/*
		foreach($collection as $c){
		print_r($c->getData());
		die();
		} */
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{

		$this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
		));
		/*
		 if (!Mage::app()->isSingleStoreMode()) {
		 $this->addColumn('store_id', array(
		 'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
		 'index'     => 'store_id',
		 'type'      => 'store',
		 'store_view'=> true,
		 'display_deleted' => true,
		 ));
		 }
		 */
		$this->addColumn('fulfilled_by', array(
            'header' => Mage::helper('ddate')->__('Fulfilled By'),
            'index'  => 'fulfilled_by',
            'type' => 'options',
        	'options' => Mage::getModel('sales2/options')->getOptions(),
            'renderer'  => 'Ciplex_Sales2_Block_Adminhtml_Sales3_Renderer_Fulfilled',
            'filter_index' => 'additional.fulfilled_by',
            'width'  => '100px', 
        	'filter_condition_callback' => array($this, '_fulfillBy'),
		 
		));
		$this->addColumn('order_pickup', array(
            'header' => Mage::helper('ddate')->__('Order<br>Pickup by'),
            'index'  => 'order_pickup',
            'type' => 'options',
			'options' => Mage::getModel('deliverydriver/orderpickup')->getPickup(),
            'filter_index' => 'pp.order_pickup',
            'width'  => '30px', 
		));
		$this->addColumn('delivery_loc', array(
            'header' => Mage::helper('ddate')->__('Internal<br>Delivery<br>Location'),
            'index'  => 'delivery_loc',
			'type' => 'options',
			'options' => Mage::getModel('deliverydriver/orderpickup')->getLocation(),
            'filter_index' => 'pp.delivery_loc',
            'width'  => '30px', 
		));
		$this->addColumn('custom_order', array(
            'header' => Mage::helper('ddate')->__('Custom<br>Order'),
            'index'  => 'custom_order',
            'type' => 'options',
            'options' => array('Y' => 'Yes', 'N' => 'No'),
            'filter_index' => 'additional.custom_order',
            'width'  => '30px', 
		));
		/*
		 $this->addColumn('ddate', array(
		 'header' => Mage::helper('ddate')->__('Delivery Date'),
		 'index'  => 'ddate',
		 'type'   => 'date',
		 'width'  => '100px',
		 ));
		 $this->addColumn('dtime', array(
		 'header' => Mage::helper('ddate')->__('Delivery Time'),
		 'index'  => 'dtime',
		 'width'  => '150px',
		 ));
		 */
		$this->addColumn('shipping_date', array(
            'header' => Mage::helper('ddate')->__('Adjusted Date'),
            'index'  => 'shipping_date',
            'type' => 'date',
            'filter_index'  => 'additional.shipping_date',
            'width'  => '100px', 
		));

		$this->addColumn('shipping_time2', array(
            'header' => Mage::helper('ddate')->__('Adjusted Time'),
            'index'  => 'shipping_time2',
            'filter_index' => 'additional.shipping_time2',
            'width'  => '100px',
            'renderer'  => 'Ciplex_Sales2_Block_Adminhtml_Sales2_Renderer_Time'
            ));
            $this->addColumn('shipping_description', array(
            'header' => Mage::helper('sales')->__('Shipping Information'),
            'index' => 'shipping_description',
            'filter_index' => 'order.shipping_description',
            ));
            $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
            ));
            $this->addColumn('deliverydriver', array(
            'header' => Mage::helper('ddate')->__('Delivery Driver'),
            'index'  => 'name',
            'type'   => 'options',
            'filter_index' => 'ddriver.name',
            'width'  => '100px',
            'options' => Mage::getModel('deliverydriver/deliverydriver')->getDrivers(),
            ));
            $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'fitler_index' => 'order.status',
            'type'  => 'options',
            'width' => '130px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
            ));

            return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		if (Mage::getSingleton('admin/session')->isAllowed('sales/sales2backend')) {
			//return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
			return $this->helper('adminhtml')->getUrl('*/*/view', array('order_id' => $row->getId()));
		}
		return false;
	}

	public function getGridUrl()
	{
		return $this->getUrl('*/*/grid', array('_current'=>true));
	}
	protected function _fulfillBy($collection, $column)
	{
		if (!$value = $column->getFilter()->getValue()) {
			return $this;
		}

		$this->getCollection()->addFieldToFilter(
            "additional.fulfilled_by"
            ,array('like'=>'%'.$value.'%'));


            return $this;
	}
}
