<?php
/**
 * @author Adjustware
 */
class MW_Ddate_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{


    public function __construct()
    {
    	parent::__construct();
        $this->setId('sales_order_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_grid_collection';
    }

    protected function _prepareCollection()
    {

    	$ddate = Mage::getModel('ddate/dtime')->getCollection();
        //$collection = Mage::getModel('sales/order')->getCollection();
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
        	)->joinLeft(
                        array('mwdtime' => $ddate->getTable('dtime')),
                        'mwdtime.dtime_id = mwddate.dtime',
                        array('mwdtime.dtime')
                        )
        	->joinLeft(
            array('users' => 'cakocustomer_entity'),
            'main_table.customer_id = users.entity_id',
            array('users.group_id')
            )
        	->joinLeft(
            array('group' => 'cakocustomer_group'),
            'users.group_id = group.customer_group_id',
            array('customer_group_code')
            )
        	->joinLeft(
            array('ord' => 'cakosales_flat_order'),
            'main_table.entity_id = ord.entity_id',
            array('ord.customer_email','shipping_description','shipping_address_id')
            )
        	->joinLeft(
            array('adr' => 'cakosales_flat_order_address'),
            'adr.parent_id = main_table.entity_id and adr.address_type = "shipping"',
            array('postcode')
            )
        	->joinleft(
            array('additional' => 'cakoorders_additional'),
            'main_table.entity_id = additional.order_id',
            array('fulfilled_by', 'shipping_date', 'shipping_time', 'custom_order')
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
        ;

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

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
            'filter_index' => 'main_table.created_at',
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
        ));

        $this->addColumn('customer_email', array(
            'header' => Mage::helper('sales')->__('Email'),
            'index' => 'customer_email',
            'filter_index' => 'ord.customer_email',
        ));
        $this->addColumn('customer_group_code', array(
            'header' => Mage::helper('sales')->__('Customer Group'),
            'index' => 'customer_group_code',
            'filter_index' => 'group.customer_group_code',
        ));
/*
        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));
*/
        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
            'filter_index' => 'main_table.grand_total',
        ));
        $this->addColumn('shipping_description', array(
            'header' => Mage::helper('sales')->__('Shipping'),
            'index' => 'shipping_description',
            'filter_index' => 'ord.shipping_description',
        ));
        $this->addColumn('postcode', array(
            'header' => Mage::helper('sales')->__('Shipping ZIP'),
            'index' => 'postcode',
            'filter_index' => 'adr.postcode',
        ));
         $this->addColumn('ddriver', array(
            'header' => Mage::helper('ddate')->__('Delivery Driver'),
            'index'  => 'name',
            'type'   => 'options',
            'width'  => '100px',
            'filter_index' => 'ddriver.name',
         	'options' => Mage::getModel('deliverydriver/deliverydriver')->getDrivers(),
        ));
        $this->addColumn('ddate', array(
            'header' => Mage::helper('ddate')->__('Delivery Date'),
            'index'  => 'ddate',
            'type'   => 'date',
            'width'  => '100px',
            'filter_index' => 'mwddate.ddate',
        ));
        $this->addColumn('dtime', array(
            'header' => Mage::helper('ddate')->__('Delivery Time'),
            'index'  => 'dtime',
            'width'  => '100px',
            'filter_index' => 'mwddate.dtime',
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'filter_index' => 'ord.status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));
        $this->addColumn('custom_order', array(
            'header' => Mage::helper('ddate')->__('Custom Order'),
            'index'  => 'custom_order',
            'type' => 'options',
            'options' => array('Y' => 'Yes', 'N' => 'No'),
            'filter_index' => 'additional.custom_order',
            'width'  => '30px',
        ));
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn('action',
                array(
                    'header'    => Mage::helper('sales')->__('Action'),
                    'width'     => '50px',
                    'type'      => 'action',
                    'getter'     => 'getId',
                    'actions'   => array(
                        array(
                            'caption' => Mage::helper('sales')->__('View'),
                            'url'     => array('base'=>'*/sales_order/view'),
                            'field'   => 'order_id'
                        )
                    ),
                    'filter'    => false,
                    'sortable'  => false,
                    'index'     => 'stores',
                    'is_system' => true,
            ));
        }
        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/cancel')) {
            $this->getMassactionBlock()->addItem('cancel_order', array(
                 'label'=> Mage::helper('sales')->__('Cancel'),
                 'url'  => $this->getUrl('*/sales_order/massCancel'),
            ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/hold')) {
            $this->getMassactionBlock()->addItem('hold_order', array(
                 'label'=> Mage::helper('sales')->__('Hold'),
                 'url'  => $this->getUrl('*/sales_order/massHold'),
            ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/unhold')) {
            $this->getMassactionBlock()->addItem('unhold_order', array(
                 'label'=> Mage::helper('sales')->__('Unhold'),
                 'url'  => $this->getUrl('*/sales_order/massUnhold'),
            ));
        }

        $this->getMassactionBlock()->addItem('pdfinvoices_order', array(
             'label'=> Mage::helper('sales')->__('Print Invoices'),
             'url'  => $this->getUrl('*/sales_order/pdfinvoices'),
        ));

        $this->getMassactionBlock()->addItem('pdfshipments_order', array(
             'label'=> Mage::helper('sales')->__('Print Packingslips'),
             'url'  => $this->getUrl('*/sales_order/pdfshipments'),
        ));

        $this->getMassactionBlock()->addItem('pdfcreditmemos_order', array(
             'label'=> Mage::helper('sales')->__('Print Credit Memos'),
             'url'  => $this->getUrl('*/sales_order/pdfcreditmemos'),
        ));

        $this->getMassactionBlock()->addItem('pdfdocs_order', array(
             'label'=> Mage::helper('sales')->__('Print All'),
             'url'  => $this->getUrl('*/sales_order/pdfdocs'),
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
        }
        return false;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }


}