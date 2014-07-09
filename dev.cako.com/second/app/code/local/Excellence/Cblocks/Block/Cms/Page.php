<?php

class Excellence_Cblocks_Block_Cms_Page extends Mage_Core_Block_Template {

    protected $blockCollection;
    protected $imageCollection;

    public function __construct() {
        $this->blockCollection = Mage::getModel('cblocks/cblocks')->getCollection();
        $this->imageCollection = Mage::getModel('cblocks/images')->getCollection();
    }

    public function hasBlocks() {
        return $this->blockCollection->getSize();
    }

    public function hasImages() {
        return $this->imageCollection->getSize();
    }

    public function getBlocks() {
        return $this->blockCollection;
    }

    public function getImages() {
        return $this->imageCollection;
    }

    public function getLinks($block) {
        $pages = explode(',', $block->getPages());
        $links = array();
        foreach ($pages as $page) {
            $cms = Mage::getModel('cms/page')->getCollection()
                    ->addFieldToFilter('identifier', $page)
                    ->getFirstItem();
            $links[Mage::getUrl($cms->getIdentifier())] = $cms->getTitle();
        }
        return $links;
    }

    public function getBlockLinks($blockId) {
        $blockLinks = Mage::getModel('cblocks/links')->getCollection()
                ->addFieldToFilter('block_id', $blockId)
        ;
        $links = array();
        foreach ($blockLinks as $link) {
            $links[$link->getLink()] = $link->getTitle();
        }
        return $links;
    }

}
