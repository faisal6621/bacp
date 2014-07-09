<?php
class Ciplex_Sales2_Block_Adminhtml_Sales3_Renderer_Fulfilled extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

	public function render(Varien_Object $row){
		$fulfilled = array();
		$ful="";
		 $row=$row->getData($this->getColumn()->getIndex());
		if($row){
			$options = Mage::getModel('sales2/options')->getOptions();
			$add=explode(',',$row);
			$i=0;
			foreach($add as $v)
			{
				$fulfilled[$i] = $options[$v];
				$i++;
			}
			$ful=implode(',',$fulfilled);
		}
		return $ful;
	}
}
?>