<?php
class Excellence_Storelocation_Block_Adminhtml_Featuredproduct extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_featuredproduct';
    $this->_blockGroup = 'storelocation';
    $this->_headerText = Mage::helper('storelocation')->__('Featured products');
    $this->_addButtonLabel = Mage::helper('storelocation')->__('Add Featured product');
    parent::__construct();
  }

  protected function _prepareLayout()
    {
        $this->setChild( 'grid',
            $this->getLayout()->createBlock( $this->_blockGroup.'/' . $this->_controller . '_grid',
            $this->_controller . '.grid')->setSaveParametersInSession(true) );
        return parent::_prepareLayout();
    }
}