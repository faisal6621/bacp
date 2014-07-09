<?php

class Excellence_Ordersuccesspage_Block_Adminhtml_CartProducts_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'ordersuccesspage';
        $this->_controller = 'adminhtml_cartProducts';

        $this->_updateButton('save', 'label', Mage::helper('ordersuccesspage')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('ordersuccesspage')->__('Delete'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('ordersuccesspage_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'ordersuccesspage_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'ordersuccesspage_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('cartproducts_data') && Mage::registry('cartproducts_data')->getId()) {
            return Mage::helper('ordersuccesspage')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('cartproducts_data')->getTitle()));
        } else {
            return Mage::helper('ordersuccesspage')->__('Add New Products Block');
        }
    }

}
