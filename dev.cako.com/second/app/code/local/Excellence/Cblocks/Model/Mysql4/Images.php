<?php

class Excellence_Cblocks_Model_Mysql4_Images extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('cblocks/images', 'image_id');
    }

}
