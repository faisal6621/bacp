<?php

class Excellence_ShippingReport_Model_Resource_Report_Shipping_Collection_Adjusted extends Mage_Sales_Model_Resource_Report_Shipping_Collection_Shipment {

    public function __construct() {
        $this->setModel('adminhtml/report_item');
        $this->_resource = Mage::getResourceModel('sales/report')->init('shipping_report/aggregate_adjusted');
        $this->setConnection($this->getResource()->getReadConnection());
    }

}
