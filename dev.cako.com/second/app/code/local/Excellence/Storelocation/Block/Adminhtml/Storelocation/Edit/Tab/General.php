<?php

class Excellence_Storelocation_Block_Adminhtml_Storelocation_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('storelocation_form', array('legend'=>Mage::helper('storelocation')->__('General')));
		 
	 $fieldset->addField('storename', 'text', array(
          'label'     => Mage::helper('storelocation')->__('Store display name'),
          'required'  => true,
          'name'      => 'storename',
	 ));

	 $fieldset->addField('storelocation', 'select', array(
          'label'     => Mage::helper('storelocation')->__('Region'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'storelocation',
		  'values'    => array(
	 array(
                  'value'     => 'San Francisco',
                  'label'     => 'San Francisco',
	 ),

	 array(
                  'value'     => 'San Jose',
                  'label'     => 'San Jose',
	 ),
	 ),
	 ));

	 $fieldset->addField('address1', 'text', array(
          'label'     => Mage::helper('storelocation')->__('Address'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'address1',
	 ));
	 $fieldset->addField('address2', 'text', array(
          'name'      => 'address2',
	 ));

	 $fieldset->addField('city', 'text', array(
          'label'     => Mage::helper('storelocation')->__('City'),
          'required'  => true,
          'name'      => 'city',
	 ));

	 $fieldset->addField('state', 'text', array(
          'label'     => Mage::helper('storelocation')->__('State'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'state',
	 ));
	 $fieldset->addField('zip', 'text', array(
         'label'     => Mage::helper('storelocation')->__('Zip'),
		 'required'  => true,
          'name'      => 'zip',
	 ));
	 $fieldset->addField('latitude', 'text', array(
         'label'     => Mage::helper('storelocation')->__('Latitude'),
		 'required'  => true,
          'name'      => 'latitude',
	 ));
	 $fieldset->addField('longitude', 'text', array(
         'label'     => Mage::helper('storelocation')->__('Longitude'),
		 'required'  => true,
          'name'      => 'longitude',
       	 'after_element_html' => "Refer <a href='http://www.gps-coordinates.net/' target='_blank'>http://www.gps-coordinates.net/</a> for latitude and longitude",
	 ));



	 $fieldset->addField('storephone', 'text', array(
          'name'      => 'storephone',
          'label'     => Mage::helper('storelocation')->__('Store Phone'),
          'title'     => Mage::helper('storelocation')->__('Store Phone'),
          'required'  => true,
	 ));
	 $fieldset->addField('holidaymessage', 'text', array(
          'name'      => 'holidaymessage',
          'label'     => Mage::helper('storelocation')->__('Holiday Closure Message'),
          'title'     => Mage::helper('storelocation')->__('Holiday Closure Message'),
	 ));
	 $fieldset->addField('shortdescription', 'editor', array(
          'name'      => 'shortdescription',
          'label'     => Mage::helper('storelocation')->__('Short Description'),
          'title'     => Mage::helper('storelocation')->__('Short Description'),
	 ));
	 $fieldset->addField('googlemapapi', 'text', array(
          'name'      => 'googlemapapi',
          'class'     => 'required-entry',
          'required'  => true,
          'label'     => Mage::helper('storelocation')->__('Store Url'),
          'title'     => Mage::helper('storelocation')->__('Store Url'),
	 ));

	  
	 $fieldset->addField('frozenproduct', 'select', array(
          'label'     => Mage::helper('storelocation')->__('Frozen product availability'),
          'name'      => 'frozenproduct',
          'values'    => array(
	 array(
                  'value'     => 'No',
                  'label'     => 'No',
	 ),

	 array(
                  'value'     => 'Yes',
                  'label'     => 'Yes',
	 ),


	 ),
	 ));
	 $fieldset->addField('green_icon', 'select', array(
          'label'     => Mage::helper('storelocation')->__('Green icon availability'),
          'name'      => 'green_icon',
          'values'    => array(
	 array(
                  'value'     => 'No',
                  'label'     => 'No',
	 ),

	 array(
                  'value'     => 'Yes',
                  'label'     => 'Yes',
	 ),


	 ),
	 ));
	 $fieldset->addField('brown_icon', 'select', array(
          'label'     => Mage::helper('storelocation')->__('Brown icon availability'),
          'name'      => 'brown_icon',
          'values'    => array(
	 array(
                  'value'     => 'No',
                  'label'     => 'No',
	 ),

	 array(
                  'value'     => 'Yes',
                  'label'     => 'Yes',
	 ),


	 ),
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