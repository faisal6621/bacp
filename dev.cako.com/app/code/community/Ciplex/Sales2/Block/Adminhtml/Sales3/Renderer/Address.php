<?php
class Ciplex_Sales2_Block_Adminhtml_Sales3_Renderer_Address extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

		public function render(Varien_Object $row){
				$html = '';
				$shippingAddressID =  $row->getData($this->getColumn()->getIndex());
				$shippingAddress =  Mage::getModel('sales/order_address')->load($shippingAddressID);
				if($shippingAddress->getCompany()){
					$html .= $shippingAddress->getCompany().'<br/>';
				}
				$street = $shippingAddress->getStreet();
				$html = $shippingAddress->getFirstname().' '.$shippingAddress->getLastname().'<br/>';
				$html .= $street[0].'<br/>';
				if(isset($street[1])){
						$html .= $street[1].'<br/>';
				}
				$html .= $shippingAddress->getCity().', '.$shippingAddress->getRegion().' '.$shippingAddress->getPostcode().'<br/>';
				if($shippingAddress->getTelephone()){
					$html .= 'T: '.$shippingAddress->getTelephone();
				}
				return $html;
		}
}
?>