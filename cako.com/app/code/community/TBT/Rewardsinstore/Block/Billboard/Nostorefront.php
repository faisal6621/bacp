<?php

class TBT_Rewardsinstore_Block_Billboard_Nostorefront extends TBT_Billboard_Block_Billboard
{
    protected function _beforeToHtml()
    {
        $title = $this->hasData('title') ? $this->getData('title') : "You must create a storefront first!";
        $displayContinueLink = $this->hasData('displayContinueLink') ? $this->getData('displayContinueLink') : true;
        $this->setTitle($title)
            ->setDisplayContinueLink(false);
        
        parent::_beforeToHtml();
        
        $block = $this->getLayout()->createBlock('tbtbillboard/billboard_section')
            ->setData('heading', "Step 1")
            ->setData('content', "Go to the <a href='{$this->getUrl('rewardsinstoreadmin/manage_storefront')}' target='_blank'>Storefront Locations</a> page.")
            ->setData('imagePath', 'images/rewardsinstore/billboard/nostorefront-step1.png');
        $this->_sections[] = $block;
        
        $block = $this->getLayout()->createBlock('tbtbillboard/billboard_section')
            ->setData('heading', "Step 2")
            ->setData('content', "Click on Add New Storefront Location.")
            ->setData('imagePath', 'images/rewardsinstore/billboard/nostorefront-step2.png');
        $this->_sections[] = $block;
        
        $block = $this->getLayout()->createBlock('tbtbillboard/billboard_section')
            ->setData('heading', "Step 3")
            ->setData('content', "Enter and Save storefront details")
            ->setData('imagePath', 'images/rewardsinstore/billboard/nostorefront-step3.png');
        $this->_sections[] = $block;
        
        $block = $this->getLayout()->createBlock('tbtbillboard/billboard_section')
            ->setData('heading', "Step 4")
            ->setData('content', "Create and manage multiple storefronts")
            ->setData('imagePath', 'images/rewardsinstore/billboard/nostorefront-step4.png');
        $this->_sections[] = $block;
        
        if ($displayContinueLink) {
            $this->_sections[] = $this->getLayout()->createBlock('tbtbillboard/billboard_section_continuelink');
        }
        
        return $this;
    }
}
