<?php

class Excellence_Storelocation_Model_Mysql4_Featuredproduct extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the featuredproduct_id refers to the key field in your database table.
        $this->_init('storelocation/featuredproduct', 'featuredproduct_id');
    }
}