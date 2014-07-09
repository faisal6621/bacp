<?php

$installer = $this;
$installer->startSetup();

/**
 * Create table 'shipping_report/aggregate_adjusted'
 */
$table = $installer->getConnection()
        ->newTable($installer->getTable('shipping_report/aggregate_adjusted'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
                ), 'Id')
        ->addColumn('period', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
                ), 'Period')
        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
                ), 'Store Id')
        ->addColumn('order_status', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
                ), 'Order Status')
        ->addColumn('shipping_description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
                ), 'Shipping Description')
        ->addColumn('orders_count', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
            'default' => '0',
                ), 'Orders Count')
        ->addColumn('total_shipping', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
                ), 'Total Shipping')
        ->addColumn('total_shipping_actual', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
                ), 'Total Shipping Actual')
        ->addIndex(
                $installer->getIdxName(
                        'shipping_report/aggregate_adjusted', array('period', 'store_id', 'order_status', 'shipping_description'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
                ), array('period', 'store_id', 'order_status', 'shipping_description'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        ->addIndex($installer->getIdxName('shipping_report/aggregate_adjusted', array('store_id')), array('store_id'))
        ->addForeignKey($installer->getFkName('shipping_report/aggregate_adjusted', 'store_id', 'core/store', 'store_id'), 'store_id', $installer->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->setComment('Sales Shipping Aggregate Adjusted');
$installer->getConnection()->createTable($table);

$installer->endSetup();
