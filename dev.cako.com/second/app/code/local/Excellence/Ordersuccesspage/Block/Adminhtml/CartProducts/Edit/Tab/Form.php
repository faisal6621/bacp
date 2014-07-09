<?php

class Excellence_Ordersuccesspage_Block_Adminhtml_CartProducts_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('cartproducts_form', array('legend' => Mage::helper('ordersuccesspage')->__('Block Information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('ordersuccesspage')->__('Block Title'),
            'name' => 'title',
            'class' => 'required-entry',
            'required' => true,
        ));

        if (Mage::getSingleton('adminhtml/session')->getCartproductsData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getCartproductsData());
            Mage::getSingleton('adminhtml/session')->setCartproductsData(null);
        } elseif (Mage::registry('cartproducts_data')) {
            $form->setValues(Mage::registry('cartproducts_data')->getData());
        }
        return parent::_prepareForm();
    }

}
