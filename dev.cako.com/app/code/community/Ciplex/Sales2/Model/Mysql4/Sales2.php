<?php

class Ciplex_Sales2_Model_Mysql4_Sales2 extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct(){
       $this->_init('sales2/sales2', 'id');
    }

}