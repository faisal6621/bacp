<?php

class Excellence_Storelocation_Model_Mysql4_Storeimages_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('storelocation/storeimages');
    }
}