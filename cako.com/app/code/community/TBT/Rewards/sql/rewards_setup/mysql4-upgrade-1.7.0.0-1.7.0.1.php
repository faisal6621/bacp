<?php
$installer = $this;

$installer->startSetup();


Mage::helper('rewards/mysql4_install')->attemptQuery($installer, "
    ALTER TABLE `{$this->getTable('rewards/transfer')}`
        ADD COLUMN `is_dev_mode` TINYINT(1) NOT NULL DEFAULT '0';
");


$install_version = Mage::getConfig ()->getNode ( 'modules/TBT_Rewards/version' );
$msg_title = "Sweet Tooth v{$install_version} was successfully installed!";
$msg_desc = "Sweet Tooth v{$install_version} was successfully installed on your store.";
Mage::helper( 'rewards/mysql4_install' )->createInstallNotice( $msg_title, $msg_desc );

// Clear cache.
Mage::helper( 'rewards/mysql4_install' )->prepareForDb();


$installer->endSetup(); 
