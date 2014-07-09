<?php

$installer = $this;
$installer->startSetup();
$installer->run(
        "CREATE TABLE `{$this->getTable('cblocks/cblocks')}` ("
        . "`cblocks_id` int(11) unsigned NOT NULL auto_increment, "
        . "`title` varchar(255) not null default '' comment 'Custom Block Title', "
        . "`pages` text not null default '' comment 'CMS Pages', "
        . "PRIMARY KEY (`cblocks_id`)"
        . ") ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);
$installer->endSetup();
