<?php

class Excellence_Posimg_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $customers = Mage::getModel('customer/customer')->getCollection()
                ->addAttributeToSelect('*')
//                ->addFieldToFilter('is_created_instore', 1)
                ->addFieldToFilter('group_id', 38)
        ;
        echo '<pre>';
        echo $customers->getSelect() . '<br>';
        $i = 1;
        foreach ($customers as $customer) {
            printf('%5d- %5s: %-50s     %s', $i, $customer->getId(), $customer->getEmail(), $customer->getName());
            echo '<br>';
            $i++;
//            print_r($customer->getData());
//            die;
        }
        echo 'EOF;<br>';

        $salesrules = Mage::getModel('salesrule/rule')->getCollection();
        foreach ($salesrules as $salesrule) {
//            print_r($salesrule->getData());
//            print_r($salesrule->getCustomerGroupIds());
//            die;
            printf("%3s: %-50s %-10s %s<br>", $salesrule->getId(), $salesrule->getName(), $salesrule->getIsActive() ? 'active' : 'inactive', in_array('38', $salesrule->getCustomerGroupIds()) ? 'yes' : 'no');
        }
    }

    public function ordersAction() {
        $orders = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToSelect('*')
                ->join(array('order_additional' => 'sales2/sales2'), 'order_additional.order_id = main_table.entity_id', array('shipment' => 'shipping_date'))
                ->setOrder('order_additional.shipping_date', 'ASC')
//                ->setOrder('created_at', 'ASC')

        ;
        echo '<pre>';
        foreach ($orders as $order) {
            if (!$order->getShipment()) {
                continue;
            }
            printf("%5s:   %-10s    %-75s %s<br>", $order->getId(), $order->getShipment(), $order->getShippingDescription(), $order->getCreatedAt());
//            print_r($order->getData());
//            die;
        }
    }

}
