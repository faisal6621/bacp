<?php
$installer = $this;
$installer->startSetup();
$installer->run(
        "CREATE TABLE `{$this->getTable('cblocks/images')}` ("
        . "`image_id` int(11) unsigned NOT NULL auto_increment, "
        . "`title` varchar(255) not null default '' comment 'Image Title', "
        . "`image` varchar(255) not null default '' comment 'Image Path', "
        . "PRIMARY KEY (`image_id`)"
        . ") ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);
$installer->endSetup();
