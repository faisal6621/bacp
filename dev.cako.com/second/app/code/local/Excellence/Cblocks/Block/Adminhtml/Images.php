<?php

class Excellence_Cblocks_Block_Adminhtml_Images extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_images';
        $this->_blockGroup = 'cblocks';
        $this->_headerText = Mage::helper('cblocks')->__('Custom Blocks Images Manager');
        $this->_addButtonLabel = Mage::helper('cblocks')->__('Add Image');
        parent::__construct();
    }

}
