<?php

$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('storelocation')};
CREATE TABLE {$this->getTable('storelocation')} (
  `storelocation_id` int(11) unsigned NOT NULL auto_increment,
  `storelocation` varchar(50) NOT NULL default '',
  `storename` varchar(50) NOT NULL default '',
  `frozenproduct` varchar(25) NOT NULL default '',
  `green_icon` varchar(25) NOT NULL default '',
  `brown_icon` varchar(25) NOT NULL default '',
  `storephone` varchar(25) NOT NULL default '',
   `holidaymessage` varchar(255) NOT NULL default '',
  `shortdescription` text(2000) NOT NULL default '',
  `googlemapapi` text(2000) NOT NULL default '',
  `address1` varchar(255) NOT NULL default '',
  `address2` varchar(255) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `state` varchar(25) NOT NULL default '',
  `zip` varchar(25) NOT NULL default '',
  `latitude` varchar(255) NOT NULL default '',
  `longitude` varchar(255) NOT NULL default '',
  `storeimages1` varchar(50) NOT NULL default '',
  `mondayfrom` varchar(25) NOT NULL default '',
  `mondayto` varchar(25) NOT NULL default '',
  `tuesdayfrom` varchar(255) NOT NULL default '',
  `tuesdayto` varchar(25) NOT NULL default '',
  `wednesdayfrom` varchar(25) NOT NULL default '',
  `wednesdayto` varchar(25) NOT NULL default '',
  `thursdayfrom` varchar(25) NOT NULL default '',
  `thursdayto` varchar(25) NOT NULL default '',
  `fridayfrom` varchar(25) NOT NULL default '',
  `fridayto` varchar(25) NOT NULL default '',
  `saturdayfrom` varchar(25) NOT NULL default '',
  `saturdayto` varchar(25) NOT NULL default '',
  `sundayfrom` varchar(255) NOT NULL default '',
   `sundayto` varchar(255) NOT NULL default '',
  
  PRIMARY KEY (`storelocation_id`)
);

-- DROP TABLE IF EXISTS {$this->getTable('storeimages')};
CREATE TABLE {$this->getTable('storeimages')} (
  `image_id` int(11) unsigned NOT NULL auto_increment,
  `storelocation_id` int(11) unsigned NOT NULL,
  `image` varchar(50) NOT NULL default '',
  `def_image` varchar(25) NOT NULL default '',
  PRIMARY KEY (`image_id`)
);

-- DROP TABLE IF EXISTS {$this->getTable('featuredproduct')};
CREATE TABLE {$this->getTable('featuredproduct')} (
  `featuredproduct_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `ranking` varchar(255) NOT NULL default '',
  PRIMARY KEY (`featuredproduct_id`)
);

-- DROP TABLE IF EXISTS {$this->getTable('storeproduct')};
CREATE TABLE {$this->getTable('storeproduct')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `store_id` int(11) unsigned NOT NULL,
  `entity_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `position` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`)
);
    ");

$installer->endSetup(); 