<?php

class Excellence_Ordersuccesspage_Model_Mysql4_CartProducts_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('ordersuccesspage/cartProducts');
    }

}
