<?php

class Excellence_storelocation_Block_Adminhtml_Featuredproduct_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
     
  	parent::__construct();
      $this->setId('featuredproduct_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('storelocation')->__('Featured Product Inormation'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('storelocation')->__('Item Information'),
          'title'     => Mage::helper('storelocation')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('storelocation/adminhtml_featuredproduct_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}