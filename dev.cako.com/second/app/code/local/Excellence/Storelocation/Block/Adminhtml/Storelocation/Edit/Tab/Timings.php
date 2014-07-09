<?php

class Excellence_Storelocation_Block_Adminhtml_Storelocation_Edit_Tab_Timings extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

	public function _construct() {
		parent::_construct();
		$this->setTemplate('storelocation/edit/tab/timings.phtml');

		 

	}
	public function getTabLabel() {
		return $this->__('Timings');
	}
	public function getHours() {
		return Mage::getModel('storelocation/storelocation')->getHours();
	}
	public function getSelected() {
		return array('mondayfrom'=> Mage::registry('storelocation_data')->getMondayfrom(),
		'mondayto'=> Mage::registry('storelocation_data')->getMondayto(),
		'tuesdayfrom'=> Mage::registry('storelocation_data')->getTuesdayfrom(),
		'tuesdayto'=> Mage::registry('storelocation_data')->getTuesdayto(),
		'wednesdayfrom'=> Mage::registry('storelocation_data')->getWednesdayfrom(),
		'wednesdayto'=> Mage::registry('storelocation_data')->getWednesdayto(),
		'thursdayfrom'=> Mage::registry('storelocation_data')->getThursdayfrom(),
		'thursdayto'=> Mage::registry('storelocation_data')->getThursdayto(),
		'fridayfrom'=> Mage::registry('storelocation_data')->getFridayfrom(),
		'fridayto'=> Mage::registry('storelocation_data')->getFridayto(),
		'saturdayfrom'=> Mage::registry('storelocation_data')->getSaturdayfrom(),
		'saturdayto'=> Mage::registry('storelocation_data')->getSaturdayto(),
		'sundayfrom'=> Mage::registry('storelocation_data')->getSundayfrom(),
		'sundayto'=> Mage::registry('storelocation_data')->getSundayto(),
		);
	}

	public function getTabTitle() {
		return $this->__('Timings');
	}

	public function canShowTab() {
		return true;
	}

	public function isHidden() {
		return false;
	}




}