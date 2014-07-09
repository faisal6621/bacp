<?php

class Excellence_Cblocks_Helper_Data extends Mage_Core_Helper_Abstract {

    public function cblocksCss() {
        $root = Mage::app()->getLayout()->getBlock('root');
        if ($root) {
            if ($root->getTemplate() == 'page/2column-left.phtml') {
                return 'css/cblocks.css';
            }
        }
        return '';
    }

}
