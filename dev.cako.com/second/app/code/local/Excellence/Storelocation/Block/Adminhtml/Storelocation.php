<?php
class Excellence_Storelocation_Block_Adminhtml_Storelocation extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_storelocation';
    $this->_blockGroup = 'storelocation';
    $this->_headerText = Mage::helper('storelocation')->__('Configure Store Locations');
    $this->_addButtonLabel = Mage::helper('storelocation')->__('Add Store');
    parent::__construct();
  }
}