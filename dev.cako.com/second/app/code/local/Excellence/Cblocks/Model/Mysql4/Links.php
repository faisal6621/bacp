<?php

class Excellence_Cblocks_Model_Mysql4_Links extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('cblocks/links', 'link_id');
    }

}
