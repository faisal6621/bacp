<?php
class Ciplex_Sales2_Block_Adminhtml_Sales2_Renderer_Time extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

		public function render(Varien_Object $row){
				$shippingTime =  $row->getData($this->getColumn()->getIndex());
				if(!isset($shippingTime) || $shippingTime == '00:00:00'){
						return '';
				}
				$time = explode(':',$shippingTime);
				$H1 = $time[0];
				if($H1 == 0){
					$H = 12;
					$A = ' AM';
				}
				if($H1 == 12){
					$H = 12;
					$A = ' PM';				
				}
				if($H1 > 12){
						$H = $H1 - 12;
						$H = sprintf("%02s", $H);
						$A = ' PM';
				}
				if($H1 > 0 && $H1 < 12){
						$H = $H1;
						$A = ' AM';
				}
				$M = $time[1];
				
				$html = $H.':'.$M.$A;
				return $html;
		}
}
?>