<?php

class Excellence_Ordersuccesspage_Model_Mysql4_CartProducts extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('ordersuccesspage/cartProducts', 'entity_id');
    }

}
