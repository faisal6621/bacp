<?php

class Excellence_Posimg_Model_Mysql4_Posimg extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the posimg_id refers to the key field in your database table.
        $this->_init('posimg/posimg', 'posimg_id');
    }
}