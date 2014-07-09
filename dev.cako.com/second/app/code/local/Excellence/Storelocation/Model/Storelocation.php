<?php

class Excellence_Storelocation_Model_Storelocation extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('storelocation/storelocation');
	}

	public function getHours()
	{
		$hours=array(
		"7am"=>"7am",
		"7:30am"=>"7:30am",
		"8am"=>"8am",
		"8:30am"=>"8:30am",
		"9am"=>"9am",
		"9:30am"=>"9:30am",
		"10am"=>"10am",
		"10:30am"=>"10:30am",
		"11am"=>"11am",
		"11:30am"=>"11:30am",
		"12pm"=>"12pm",
		"12:30pm"=>"12:30pm",
		"1pm"=>"1pm",
		"1:30pm"=>"1:30pm",
		"2pm"=>"2pm",
		"2:30pm"=>"2:30pm",
		"3pm"=>"3pm",
		"3:30pm"=>"3:30pm",
		"4pm"=>"4pm",
		"4:30pm"=>"4:30pm",
		"5pm"=>"5pm",
		"5:30pm"=>"5:30pm",
		"6pm"=>"6pm",
		"6:30pm"=>"6:30pm",
		"7pm"=>"7pm",
		"7:30pm"=>"7:30pm",
		"8pm"=>"8am",
		"8:30pm"=>"8:30pm",
		"9pm"=>"9pm",
		"9:30pm"=>"9:30pm",
		"10pm"=>"10pm",
		"10:30pm"=>"10:30pm",
		"11pm"=>"11pm",
		"11:30pm"=>"11:30pm",
		"12am"=>"12am",
		"12:30am"=>"12:30am",
		"1am"=>"1am",
		"1:30am"=>"1:30am",
		"2am"=>"2am",
		"2:30am"=>"2:30am",
		"3am"=>"3am",
		"3:30am"=>"3:30am",
		"4am"=>"4am",
		"4:30am"=>"4:30am",
		"5am"=>"5am",
		"5:30am"=>"5:30am",
		"6am"=>"6am",
		"6:30am"=>"6:30am",
		);
		return $hours;
		
	}
}