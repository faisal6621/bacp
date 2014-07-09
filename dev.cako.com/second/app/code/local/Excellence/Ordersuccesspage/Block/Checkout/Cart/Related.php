<?php

class Excellence_Ordersuccesspage_Block_Checkout_Cart_Related extends Mage_Catalog_Block_Product_List_Related {

    protected function _prepareData() {
        $inCartItems = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
        foreach ($inCartItems as $item) {
            $product = Mage::getModel('catalog/product');
        }
    }

}
