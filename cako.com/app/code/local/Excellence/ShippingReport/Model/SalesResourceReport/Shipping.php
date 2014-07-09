<?php

class Excellence_ShippingReport_Model_SalesResourceReport_Shipping extends Mage_Sales_Model_Resource_Report_Shipping {

    public function aggregate($from = null, $to = null) {
        // convert input dates to UTC to be comparable with DATETIME fields in DB
        $from = $this->_dateToUtc($from);
        $to = $this->_dateToUtc($to);
        $this->_checkDates($from, $to);
        $this->_aggregateByOrderCreatedAt($from, $to);
        $this->_aggregateByShippingCreatedAt($from, $to);
        $this->_aggregateByShippingAdjusted($from, $to);
        $this->_setFlagData(Mage_Reports_Model_Flag::REPORT_SHIPPING_FLAG_CODE);
        return $this;
    }

    protected function _aggregateByShippingAdjusted($from, $to) {
        $table = $this->getTable('shipping_report/aggregate_adjusted');
        $sourceTable = $this->getTable('sales2/sales2');
        $orderTable = $this->getTable('sales/order');
        $adapter = $this->_getWriteAdapter();
        $adapter->beginTransaction();

        try {
            if ($from !== null || $to !== null) {
                $subSelect = $this->_getTableDateRangeRelatedSelect(
                        $sourceTable, $orderTable, array('order_id' => 'entity_id'), 'shipping_date', 'created_at', $from, $to
//                        $orderTable, $sourceTable, array('entity_id' => 'order_id'), 'created_at', 'shipping_date', $from, $to
//                        $orderTable, $sourceTable, array('entity_id' => 'order_id'), 'shipping_date', 'created_at', $from, $to
//                        $sourceTable, $orderTable, array('order_id' => 'entity_id'), 'created_at', 'shipping_date', $from, $to
                );
//                echo $subSelect;die;
            } else {
                $subSelect = null;
            }

            $this->_clearTableByDateRange($table, $from, $to, $subSelect);
            // convert dates from UTC to current admin timezone
            $periodExpr = $adapter->getDatePartSql(
                    $this->getStoreTZOffsetQuery(
                            array('source_table' => $sourceTable), 'source_table.shipping_date', $from, $to
                    )
            );
//            echo $periodExpr;
//            die;
            $ifnullBaseShippingCanceled = $adapter->getIfNullSql('order_table.base_shipping_canceled', 0);
            $ifnullBaseShippingRefunded = $adapter->getIfNullSql('order_table.base_shipping_refunded', 0);
            $columns = array(
                'period' => 'DATE(source_table.shipping_date)', #$periodExpr,
                'store_id' => 'order_table.store_id',
                'order_status' => 'order_table.status',
                'shipping_description' => 'order_table.shipping_description',
                'orders_count' => new Zend_Db_Expr('COUNT(order_table.entity_id)'),
                'total_shipping' => new Zend_Db_Expr('SUM((order_table.base_shipping_amount - '
                        . "{$ifnullBaseShippingCanceled}) * order_table.base_to_global_rate)"),
                'total_shipping_actual' => new Zend_Db_Expr('SUM((order_table.base_shipping_invoiced - '
                        . "{$ifnullBaseShippingRefunded}) * order_table.base_to_global_rate)"),
            );

            $select = $adapter->select();
            $select->from(array('source_table' => $sourceTable), $columns)
                    ->joinInner(
                            array('order_table' => $orderTable), $adapter->quoteInto(
                                    'source_table.order_id = order_table.entity_id AND order_table.state != ?', Mage_Sales_Model_Order::STATE_CANCELED), array()
                    )
                    ->useStraightJoin();

            $filterSubSelect = $adapter->select()
                    ->from(array('filter_source_table' => $sourceTable), 'MIN(filter_source_table.id)')
                    ->where('filter_source_table.order_id = source_table.order_id');

            if ($subSelect !== null) {
                $select->having($this->_makeConditionFromDateRangeSelect($subSelect, 'period'));
            }

            $select->where('source_table.id = (?)', new Zend_Db_Expr($filterSubSelect));
            unset($filterSubSelect);

            $select->group(array(
                #$periodExpr,
                'DATE(source_table.shipping_date)',
                'order_table.store_id',
                'order_table.status',
                'order_table.shipping_description'
            ));

            $helper = Mage::getResourceHelper('core');
            $insertQuery = $helper->getInsertFromSelectUsingAnalytic($select, $table, array_keys($columns));
            $adapter->query($insertQuery);

            $select->reset();
            $columns = array(
                'period' => 'period',
                'store_id' => new Zend_Db_Expr(Mage_Core_Model_App::ADMIN_STORE_ID),
                'order_status' => 'order_status',
                'shipping_description' => 'shipping_description',
                'orders_count' => new Zend_Db_Expr('SUM(orders_count)'),
                'total_shipping' => new Zend_Db_Expr('SUM(total_shipping)'),
                'total_shipping_actual' => new Zend_Db_Expr('SUM(total_shipping_actual)'),
            );

            $select
                    ->from($table, $columns)
                    ->where('store_id != ?', 0);

            if ($subSelect !== null) {
                $select->where($this->_makeConditionFromDateRangeSelect($subSelect, 'period'));
            }

            $select->group(array(
                'period',
                'order_status',
                'shipping_description'
            ));
            $insertQuery = $helper->getInsertFromSelectUsingAnalytic($select, $table, array_keys($columns));
//            die;
            $adapter->query($insertQuery);
        } catch (Exception $e) {
            $adapter->rollBack();
            throw $e;
        }

        $adapter->commit();
        return $this;
    }

}
