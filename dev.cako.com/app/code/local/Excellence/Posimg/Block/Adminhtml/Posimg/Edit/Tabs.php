<?php

class Excellence_Posimg_Block_Adminhtml_Posimg_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('posimg_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('posimg')->__('POS Images'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('posimg')->__('Export Data'),
            'title' => Mage::helper('posimg')->__('Export Data'),
            'content' => $this->getLayout()->createBlock('posimg/adminhtml_posimg_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}
