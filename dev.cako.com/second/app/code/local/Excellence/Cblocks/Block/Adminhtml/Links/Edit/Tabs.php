<?php

class Excellence_Cblocks_Block_Adminhtml_Links_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('links_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('cblocks')->__('Block'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('cblocks')->__('Information'),
            'title' => Mage::helper('cblocks')->__('Information'),
            'content' => $this->getLayout()->createBlock('cblocks/adminhtml_links_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}
