<?php

class Excellence_Storelocation_Model_Mysql4_Storelocation extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the storelocation_id refers to the key field in your database table.
        $this->_init('storelocation/storelocation', 'storelocation_id');
    }
}