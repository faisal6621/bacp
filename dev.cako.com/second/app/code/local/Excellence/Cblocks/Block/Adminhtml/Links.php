<?php

class Excellence_Cblocks_Block_Adminhtml_Links extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_links';
        $this->_blockGroup = 'cblocks';
        $this->_headerText = Mage::helper('cblocks')->__('Custom Blocks Links Manager');
        $this->_addButtonLabel = Mage::helper('cblocks')->__('Add Link');
        parent::__construct();
    }

}
