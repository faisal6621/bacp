<?php

class Excellence_Cblocks_Model_Cblocks extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('cblocks/cblocks');
    }

}
