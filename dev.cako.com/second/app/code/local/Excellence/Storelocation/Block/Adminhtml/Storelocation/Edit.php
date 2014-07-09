<?php

class Excellence_Storelocation_Block_Adminhtml_Storelocation_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'storelocation';
        $this->_controller = 'adminhtml_storelocation';
        
        $this->_updateButton('save', 'label', Mage::helper('storelocation')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('storelocation')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('storelocation_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'storelocation_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'storelocation_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('storelocation_data') && Mage::registry('storelocation_data')->getId() ) {
            return Mage::helper('storelocation')->__("Edit Store Details", $this->htmlEscape(Mage::registry('storelocation_data')->getTitle()));
        } else {
            return Mage::helper('storelocation')->__('Add Store');
        }
    }
}