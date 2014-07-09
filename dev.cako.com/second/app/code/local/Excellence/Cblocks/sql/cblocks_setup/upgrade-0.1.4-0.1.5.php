<?php

$installer = $this;
$installer->startSetup();
$installer->run(
        "CREATE TABLE `{$this->getTable('cblocks/links')}` ("
        . "`link_id` int(11) unsigned NOT NULL auto_increment, "
        . "`block_id` int(11) unsigned NOT NULL comment 'Custom Block ID', "
        . "`title` varchar(255) not null default '' comment 'Link Title', "
        . "`link` varchar(255) not null default '' comment 'Link URL', "
        . "PRIMARY KEY (`link_id`)"
        . ") ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);
$installer->endSetup();
