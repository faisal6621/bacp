<?php

class Excellence_Cblocks_Block_Adminhtml_Images_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        if ($row->getData($this->getColumn()->getIndex())) {
            $html = '<img style="max-height:50px; max-width:50px;" ';
            $html .= 'id="' . $this->getColumn()->getId() . '" ';
            $html .= 'src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $row->getData($this->getColumn()->getIndex()) . '"';
            $html .= 'class="grid-image ' . $this->getColumn()->getInlineCss() . '"/>';
            return $html;
        }
        return 'no-image';
    }

}
