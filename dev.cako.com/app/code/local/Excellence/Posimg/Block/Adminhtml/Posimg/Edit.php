<?php

class Excellence_Posimg_Block_Adminhtml_Posimg_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'posimg';
        $this->_controller = 'adminhtml_posimg';

        $this->_updateButton('save', 'label', Mage::helper('posimg')->__('Export Data'));
        $this->_removeButton('back');

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('posimg_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'posimg_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'posimg_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('posimg_data') && Mage::registry('posimg_data')->getId()) {
            return Mage::helper('posimg')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('posimg_data')->getTitle()));
        } else {
            return Mage::helper('posimg')->__('Export POS Images Data');
        }
    }

}
