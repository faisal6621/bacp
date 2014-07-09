<?php
/**
/**
class TBT_Rewards_Helper_Config_Paypal extends TBT_Rewards_Helper_Config {

    public function getPaypalCheckoutFee($store = null) {
        $fee = 0;
        if ( Mage::getStoreConfig('rewards/checkout/enable_paypal_checkout_fee', $store) ) {
            $fee = 0.01;
        }
        return $fee;
    }
}