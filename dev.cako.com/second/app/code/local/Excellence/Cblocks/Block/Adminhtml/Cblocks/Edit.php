<?php

class Excellence_Cblocks_Block_Adminhtml_Cblocks_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'cblocks';
        $this->_controller = 'adminhtml_cblocks';

        $this->_updateButton('save', 'label', Mage::helper('cblocks')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('cblocks')->__('Delete Item'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('cblocks_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'cblocks_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'cblocks_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('cblocks_data') && Mage::registry('cblocks_data')->getId()) {
            return Mage::helper('cblocks')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('cblocks_data')->getTitle()));
        } else {
            return Mage::helper('cblocks')->__('Add New Block');
        }
    }

}
