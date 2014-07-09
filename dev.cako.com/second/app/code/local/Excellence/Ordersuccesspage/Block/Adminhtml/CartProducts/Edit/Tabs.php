<?php

class Excellence_Ordersuccesspage_Block_Adminhtml_CartProducts_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('cartproducts_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('ordersuccesspage')->__('Block'));
    }

    protected function _prepareLayout() {
        $this->addTab('block_section', array(
            'label' => Mage::helper('ordersuccesspage')->__('Block'),
            'title' => Mage::helper('ordersuccesspage')->__('Block'),
            'content' => $this->getLayout()->createBlock('ordersuccesspage/adminhtml_cartProducts_edit_tab_form')->toHtml(),
        ));

        $this->addTab('products_section', array(
            'label' => Mage::helper('ordersuccesspage')->__('Products'),
            'title' => Mage::helper('ordersuccesspage')->__('Products'),
            'url' => $this->getUrl('*/*/products', array('_current' => true)),
            'class' => 'ajax',
        ));

        return parent::_prepareLayout();
    }

}
