<?php

$installer = $this;
$installer->startSetup();
$installer->run(
        "CREATE TABLE `{$this->getTable('ordersuccesspage/cartProducts')}` ("
        . "`entity_id` int(11) unsigned NOT NULL auto_increment, "
        . "`title` varchar(255) default '' COMMENT 'Products Block Title', "
        . "`product_ids` text NOT NULL default '' COMMENT 'Product IDs', "
        . "PRIMARY KEY (`entity_id`)"
        . ") ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);
