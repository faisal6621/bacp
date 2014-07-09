
<?php

class Excellence_Storelocation_Block_Adminhtml_Storelocation_Edit_Tab_Fproduct extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('storelocation_form', array('legend'=>Mage::helper('storelocation')->__('Featured Products')));
		 $product=Mage::getModel('storelocation/featuredproduct')->getProduct();
		$fieldset->addField('fproduct1', 'select', array(
          'label'     => Mage::helper('storelocation')->__('Product 1'),
          'name'      => 'fproduct1',
		  'values'    => $product
		));
		$fieldset->addField('fproduct2', 'select', array(
          'label'     => Mage::helper('storelocation')->__('Product 2'),
       	  'name'      => 'fproduct2',
		  'values'    => $product
		));
		$fieldset->addField('fproduct3', 'select', array(
       'label'     => Mage::helper('storelocation')->__('Product 3'),    
      'name'      => 'fproduct3',
		  'values' => $product
		));
		$fieldset->addField('fproduct4', 'select', array(
         'label'     => Mage::helper('storelocation')->__('Product 4'),	  
		'name'      => 'fproduct4',
		  'values'    => $product
		));
		$fieldset->addField('fproduct5', 'select', array(
			 'label'     => Mage::helper('storelocation')->__('Product 5'),
			 'name'      => 'fproduct5',
		  	 'values'    => $product
		));
		$fieldset->addField('fproduct6', 'select', array(
			 'label'     => Mage::helper('storelocation')->__('Product 6'),
			 'name'      => 'fproduct6',
		  	'values'    => $product
		)); 
		if ( Mage::getSingleton('adminhtml/session')->getStorelocationData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getStorelocationData());
			Mage::getSingleton('adminhtml/session')->setStorelocationData(null);
		} elseif ( Mage::registry('storelocation_data') ) {
			$form->setValues(Mage::registry('storelocation_data')->getData());
		}
		return parent::_prepareForm();
	}
}