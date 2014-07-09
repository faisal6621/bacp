<?php

class Excellence_Posimg_Model_Mysql4_Posimg_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('posimg/posimg');
    }
}