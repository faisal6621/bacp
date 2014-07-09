<?php

class Excellence_Posimg_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $customers = Mage::getModel('customer/customer')->getCollection()
                ->addAttributeToSelect('*')
//                ->addFieldToFilter('is_created_instore', 1)
                ->addFieldToFilter('group_id', 38)
        ;
        echo '<pre>';
//        echo $customers->getSelect() . '<br>';
        $i = 1;
        foreach ($customers as $customer) {
            printf('%5d- %5s: %-35s     %s', $i, $customer->getId(), $customer->getEmail(), $customer->getName());
            echo '<br>';
            $i++;
//            print_r($customer->getData());
//            die;
        }
//        $this->loadLayout();
//        $this->renderLayout();
    }

}
