<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addConstraint(
        'FK_REWARDSINSTORE_STOREFRONT_WEBSITE',
        $installer->getTable('rewardsinstore/storefront'), 
        'website_id',
        $installer->getTable('core/website'), 
        'website_id',
        'cascade', 
        'cascade'
);
    
$installer->addCashierAdminRole();

$installer->postDefaultInstallNotice();
$installer->endSetup();

?>
