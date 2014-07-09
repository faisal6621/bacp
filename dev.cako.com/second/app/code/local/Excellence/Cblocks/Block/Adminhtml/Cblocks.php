<?php

class Excellence_Cblocks_Block_Adminhtml_Cblocks extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_cblocks';
        $this->_blockGroup = 'cblocks';
        $this->_headerText = Mage::helper('cblocks')->__('Custom Blocks Manager');
        $this->_addButtonLabel = Mage::helper('cblocks')->__('Add Block');
        parent::__construct();
    }

}
