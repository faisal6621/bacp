<?php
class Excellence_Storelocation_Block_Store extends Mage_Core_Block_Template
{
	public function _prepareLayout()
	{
		return parent::_prepareLayout();
	}
	public function getStoreInfo() {
		$id= $this->getRequest()->getParam('id');
		$coll=Mage::getModel('storelocation/storelocation')->load($id);

		return $coll;

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
	public function getGalleryImages($id)
	{
		$images=Mage::getModel('storelocation/storeimages')->getCollection()
		->addFieldToFilter('storelocation_id',$id);
		return $images;
	}
}