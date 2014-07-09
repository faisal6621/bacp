<?php
class Excellence_Posimg_Block_Adminhtml_Posimg extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_posimg';
    $this->_blockGroup = 'posimg';
    $this->_headerText = Mage::helper('posimg')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('posimg')->__('Add Item');
    parent::__construct();
  }
}