<?php

class Ciplex_Cakoshipping_Model_Observer {

    public function catalogProductLoadAfter(Varien_Event_Observer $observer) {
        // set the additional options on the product
        $action = Mage::app()->getFrontController()->getAction();
        if ($action) {
            if ($action->getFullActionName() == 'checkout_cart_add' || $action->getFullActionName() == 'checkout_cart_updateItemOptions') {

                // assuming you are posting your custom form values in an array called extra_options...
                if ($option = $action->getRequest()->getParam('pickup-delivery')) {

                    $product = $observer->getProduct();

                    // add to the additional options array
                    $additionalOptions = array();
                    if ($additionalOption = $product->getCustomOption('additional_options')) {
                        $additionalOptions = (array) unserialize($additionalOption->getValue());
                    }

                    $additionalOptions[] = array(
                        'label' => 'Delivery type',
                        'value' => $option,
                    );
                    $additionalOptions[] = array(
                        'label' => 'Delivery Location',
                        'value' => $action->getRequest()->getParam('location'),
                    );
                    $additionalOptions[] = array(
                        'label' => 'Delivery Date',
                        'value' => $action->getRequest()->getParam('delivery_date'),
                    );
                    $additionalOptions[] = array(
                        'label' => 'Delivery Time',
                        'value' => $action->getRequest()->getParam('delivery_time'),
                    );
                    // add the additional options array with the option code additional_options
                    $observer->getProduct()
                            ->addCustomOption('additional_options', serialize($additionalOptions));
                }
            }
        }
    }

    public function salesConvertQuoteItemToOrderItem(Varien_Event_Observer $observer) {
        $quoteItem = $observer->getItem();
        if ($additionalOptions = $quoteItem->getOptionByCode('additional_options')) {
            $orderItem = $observer->getOrderItem();
            $options = $orderItem->getProductOptions();
            $options['additional_options'] = unserialize($additionalOptions->getValue());
            $orderItem->setProductOptions($options);
        }
    }

}
