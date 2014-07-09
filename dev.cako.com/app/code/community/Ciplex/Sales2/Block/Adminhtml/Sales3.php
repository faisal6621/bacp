<?php  

class Ciplex_Sales2_Block_Adminhtml_Sales3 extends Mage_Adminhtml_Block_Widget_Grid_Container {
    
    public function __construct()
    {
        $this->_controller = 'adminhtml_sales3';
        $this->_blockGroup = "sales2";
        $this->_headerText = Mage::helper('sales')->__('Orders');
        $this->_addButtonLabel = Mage::helper('sales')->__('Create New Order');
        parent::__construct();

        $this->_removeButton('add');

    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/sales_order_create/start');
    }
}