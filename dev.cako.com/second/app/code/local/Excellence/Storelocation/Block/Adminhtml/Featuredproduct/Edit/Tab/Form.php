<?php

class Excellence_Storelocation_Block_Adminhtml_Featuredproduct_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('featuredproduct_form', array('legend'=>Mage::helper('storelocation')->__('Item information')));
     	$product=Mage::getModel('storelocation/featuredproduct')->getProduct();
      $fieldset->addField('product_id', 'select', array(
          'label'     => Mage::helper('storelocation')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
      	  'values'    => $product,
          'name'      => 'product_id',
      ));

      $fieldset->addField('ranking', 'text', array(
          'label'     => Mage::helper('storelocation')->__('Ranking'),
          'required'  => false,
          'name'      => 'ranking',
	  ));
	
      if ( Mage::getSingleton('adminhtml/session')->getFeaturedproductData() )
      {
      //    $form->setValues(Mage::getSingleton('adminhtml/session')->getFeaturedproductData());
      //    Mage::getSingleton('adminhtml/session')->setFeaturedproductData(null);
      } elseif ( Mage::registry('storelocation_data') ) {
          $form->setValues(Mage::registry('storelocation_data')->getData());
      }
      return parent::_prepareForm();
  }
}