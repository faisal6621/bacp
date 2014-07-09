<?php

class Excellence_Ordersuccesspage_Model_CartProducts extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('ordersuccesspage/cartProducts');
    }

}
