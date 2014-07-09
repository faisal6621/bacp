<?php

class Ciplex_Sales2_Model_Options extends Mage_Core_Model_Abstract {

    protected function _construct(){
       $this->_init('sales2/options');
    }

		public function getOptions(){
			
				$options = array();
				$options[1] = 'Metreon CC';
				$options[2] = 'SFC';
				$options[3] = 'Union Square';
				$options[4] = 'Japantown';
				$options[5] = 'Valley Fair';
				$options[6] = 'Oakridge';
				$options[7] = 'Metreon IC';
				$options[8] = 'Breakfast';
				$options[9] = 'Cako Kitchen';
	
				return $options;
		}
}