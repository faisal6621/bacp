<?php

class TBT_Rewardsinstore_Block_Billboard_Nolicense extends TBT_Billboard_Block_Billboard
{
    protected function _beforeToHtml()
    {
        $title = $this->hasData('title') ? $this->getData('title') : "Oops! Please specify your license key.";
        $displayContinueLink = $this->hasData('displayContinueLink') ? $this->getData('displayContinueLink') : true;
        $this->setTitle($title)
            ->setDisplayContinueLink(false);
        
        parent::_beforeToHtml();
        
        $block = $this->getLayout()->createBlock('tbtbillboard/billboard_section')
            ->setData('heading', "Step 1")
            ->setData('content', "Go to Sweet Tooth Instore <a href='{$this->getUrl('adminhtml/system_config/edit/section/rewardsinstore')}' target='_blank'>configuration</a>.")
            ->setData('imagePath', 'images/rewardsinstore/billboard/nolicense-step1.png');
        $this->_sections[] = $block;
        
        $block = $this->getLayout()->createBlock('tbtbillboard/billboard_section')
            ->setData('heading', "Step 2")
            ->setData('content', "Enter license key from purchasing email.")
            ->setData('imagePath', 'images/rewardsinstore/billboard/nolicense-step2.png');
        $this->_sections[] = $block;
        
        if ($displayContinueLink) {
            $this->_sections[] = $this->getLayout()->createBlock('tbtbillboard/billboard_section_continuelink');
        }
        
        return $this;
    }
}
