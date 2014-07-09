<?php

class Excellence_Cblocks_Block_Adminhtml_Images_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('images_form', array('legend' => Mage::helper('cblocks')->__('Item information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('cblocks')->__('Block Title'),
            'name' => 'title',
            'class' => 'required-entry',
            'required' => true,
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('cblocks')->__('Image'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'image',
        ));

        $fieldset->addField('link', 'text', array(
            'label' => Mage::helper('cblocks')->__('Link'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'link',
        ));

        if (Mage::getSingleton('adminhtml/session')->getImagesData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getImagesData());
            Mage::getSingleton('adminhtml/session')->setImagesData(null);
        } elseif (Mage::registry('images_data')) {
            $form->setValues(Mage::registry('images_data')->getData());
        }
        return parent::_prepareForm();
    }

}
