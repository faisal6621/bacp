<?php

class Excellence_Storelocation_Block_Adminhtml_Storelocation_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('storelocation_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('storelocation')->__('Store Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('storelocation')->__('General'),
          'title'     => Mage::helper('storelocation')->__('General'),
          'content'   => $this->getLayout()->createBlock('storelocation/adminhtml_storelocation_edit_tab_general')->toHtml(),
      ));
       $this->addTab('form_sections1', array(
          'label'     => Mage::helper('storelocation')->__('Images'),
          'title'     => Mage::helper('storelocation')->__('Images'),
          'content'   => $this->getLayout()->createBlock('storelocation/adminhtml_storelocation_edit_tab_image')->toHtml(),
      ));
       $this->addTab('form_sections2', array(
          'label'     => Mage::helper('storelocation')->__('Store Hours'),
          'title'     => Mage::helper('storelocation')->__('Store Hours'),
          'content'   => $this->getLayout()->createBlock('storelocation/adminhtml_storelocation_edit_tab_timings')->toHtml(),
      ));
        $this->addTab('form_sections3', array(
          'label'     => Mage::helper('storelocation')->__('Featured Products'),
          'title'     => Mage::helper('storelocation')->__('Featured Products'),
          'content'   => $this->getLayout()->createBlock('storelocation/adminhtml_storelocation_edit_tab_fproduct')->toHtml(),
      ));
    // $this->addTab('form_section1', array(
      //   'label'     => Mage::helper('storelocation')->__('F Products'),
        // 'title'     => Mage::helper('storelocation')->__('F Products'),
         //'url'       => $this->getUrl('*/*/products', array('_current' => true)),
        // 'class'     => 'ajax',
      //));
      $this->addTab('products', array(
            'label' => Mage::helper('storelocation')->__('Products'),
            'url' => $this->getUrl('*/*/products', array('_current' => true)),
            'class' => 'ajax',
        ));
      return parent::_beforeToHtml();
  }
}