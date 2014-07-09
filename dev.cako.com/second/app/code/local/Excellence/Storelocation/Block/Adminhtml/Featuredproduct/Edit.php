<?php

class Excellence_Storelocation_Block_Adminhtml_Featuredproduct_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'storelocation';
        $this->_controller = 'adminhtml_featuredproduct';
         $this->_mode = 'edit';
        $this->_updateButton('save', 'label', Mage::helper('storelocation')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('storelocation')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
          		 function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";  
    }

    public function getHeaderText()
    {
       
            return Mage::helper('storelocation')->__('Add Featured product');
      
    }
}