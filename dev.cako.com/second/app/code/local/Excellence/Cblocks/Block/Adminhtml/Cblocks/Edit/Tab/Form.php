<?php

class Excellence_Cblocks_Block_Adminhtml_Cblocks_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('cblocks_form', array('legend' => Mage::helper('cblocks')->__('Item information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('cblocks')->__('Block Title'),
            'name' => 'title',
            'class' => 'required-entry',
            'required' => true,
        ));

        $fieldset->addField('pages', 'multiselect', array(
            'label' => Mage::helper('cblocks')->__('CMS Pages'),
            'name' => 'pages',
            'class' => 'required-entry',
            'required' => true,
            'values' => Mage::getModel('cms/page')->getCollection()->toOptionArray(), #Excellence_Cblocks_Model_Cms_Pages::getOptionArray(), 
        ));

        if (Mage::getSingleton('adminhtml/session')->getCblocksData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getCblocksData());
            Mage::getSingleton('adminhtml/session')->setCblocksData(null);
        } elseif (Mage::registry('cblocks_data')) {
            $form->setValues(Mage::registry('cblocks_data')->getData());
        }
        return parent::_prepareForm();
    }

}
