<?php

class Excellence_Cblocks_Block_Adminhtml_Links_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('links_form', array('legend' => Mage::helper('cblocks')->__('Item information')));

        $fieldset->addField('block_id', 'select', array(
            'label' => Mage::helper('cblocks')->__('Block'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'block_id',
            'values' => Excellence_Cblocks_Model_Source_Cblocks::getOptionArray(),
        ));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('cblocks')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('link', 'text', array(
            'label' => Mage::helper('cblocks')->__('URL'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'link',
        ));

        if (Mage::getSingleton('adminhtml/session')->getLinksData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getLinksData());
            Mage::getSingleton('adminhtml/session')->setLinksData(null);
        } elseif (Mage::registry('links_data')) {
            $form->setValues(Mage::registry('links_data')->getData());
        }
        return parent::_prepareForm();
    }

}
