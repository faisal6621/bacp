<?php

class Excellence_Posimg_Block_Adminhtml_Posimg_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('posimg_form', array('legend' => Mage::helper('posimg')->__('Item information')));

        $fieldset->addField('skip', 'checkbox', array(
            'label' => Mage::helper('posimg')->__('Skip images creation if already exists?'),
            'name' => 'skip',
            'onclick' => 'this.value = this.checked ? 1 : 0',
        ));

        if (Mage::getSingleton('adminhtml/session')->getPosimgData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getPosimgData());
            Mage::getSingleton('adminhtml/session')->setPosimgData(null);
        } elseif (Mage::registry('posimg_data')) {
            $form->setValues(Mage::registry('posimg_data')->getData());
        }
        return parent::_prepareForm();
    }

}
