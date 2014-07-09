<?php

$installer = $this;
$installer->startSetup();
$installer->run(
        "ALTER TABLE `{$this->getTable('cblocks/images')}` "
        . "ADD COLUMN `link` varchar(255) not null default '' comment 'Image Link URL';"
);
$installer->endSetup();
