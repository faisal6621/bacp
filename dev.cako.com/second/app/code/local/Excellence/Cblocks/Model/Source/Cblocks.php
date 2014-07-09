<?php

class Excellence_Cblocks_Model_Source_Cblocks {

    static public function getOptionArray() {
        $cblocks = Mage::getModel('cblocks/cblocks')->getCollection();
        $options = array(
            array(
                'label' => 'Please select...',
                'value' => '',
            )
        );
        foreach ($cblocks as $block) {
            $options[] = array(
                'label' => $block->getTitle(),
                'value' => $block->getId(),
            );
        }
        return $options;
    }

    static public function getOptions() {
        $cblocks = Mage::getModel('cblocks/cblocks')->getCollection();
        $options = array();
        foreach ($cblocks as $block) {
            $options[$block->getId()] = $block->getTitle();
        }
        return $options;
    }

}
