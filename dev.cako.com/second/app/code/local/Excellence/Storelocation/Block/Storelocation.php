<?php
class Excellence_Storelocation_Block_Storelocation extends Mage_Core_Block_Template
{
	public function _prepareLayout()
	{
		return parent::_prepareLayout();
	}

	public function getStorelocation()
	{
		if (!$this->hasData('storelocation')) {
			$this->setData('storelocation', Mage::registry('storelocation'));
		}
		return $this->getData('storelocation');

	}
	public function getStoreCollection()
	{
		$coll=Mage::getModel('storelocation/storelocation')->getCollection();
		return $coll;

	}
	public function getBaseimageUrl($id)
	{
		$coll=Mage::getModel('storelocation/storeimages')->getCollection();
		$coll->addFieldToFilter('storelocation_id',$id)
		->addFieldToFilter('def_image',1);
		foreach($coll as $v)
		{
			$imagename=$v->getImage();
		}
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$imagename;
	}

	public function getHours($id)
	{
		$coll=Mage::getModel('storelocation/storelocation')->load($id);
		$day=array();

		$day['Mon']=$coll->getMondayfrom()."-".$coll->getMondayto();
		$day['Tues']=$coll->getTuesdayfrom()."-".$coll->getTuesdayto();
		$day['Wed']=$coll->getWednesdayfrom()."-".$coll->getWednesdayto();
		$day['Thurs']=$coll->getThursdayfrom()."-".$coll->getThursdayto();
		$day['Fri']=$coll->getFridayfrom()."-".$coll->getFridayto();
		$day['Sat']=$coll->getSaturdayfrom()."-".$coll->getSaturdayto();
		$day['Sun']=$coll->getSundayfrom()."-".$coll->getSundayto();
		$temptime="";
		$html="";
		$j=0;
		$day1="Sun";
		foreach($day as $i=>$v)
		{

			if($v==$temptime)
			{
				$day2=$i;
				$html[$j]=$day1."-".$day2." ".$temptime;
			}
			else{

				$j++;
				$html[$j]=$i." ".$v;
				$day1=$i;
			}
			$temptime=$v;
			 
		}
		return $html;
	}

	public function getStringBetween($str,$from,$to)
	{
		$sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
		return substr($sub,0,strpos($sub,$to));
	}
}
