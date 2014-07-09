<?php

class Excellence_ShippingReport_Block_Adminhtml_ReportSalesShipping_Grid extends Mage_Adminhtml_Block_Report_Sales_Shipping_Grid {

    public function getResourceCollectionName() {
        return ($this->getFilterData()->getData('report_type') == 'adjusted_date') ?
                'shipping_report/report_shipping_collection_adjusted' :
                parent::getResourceCollectionName();
    }

}
