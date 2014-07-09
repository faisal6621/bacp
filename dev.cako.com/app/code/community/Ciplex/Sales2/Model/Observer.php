<?php

class Ciplex_Sales2_Model_Observer {

    public function handleOrderCreated($observer) 
    {
        $orders = $observer->getOrders(); // multishipping
        if (!$orders){ // all other situalions like single checkout, goofle checkout, admin 
            $orders  = array($observer->getOrder());
        }
				$incrementId = $orders[0]->getIncrementId();
				$entityId = $orders[0]->getEntityId();

				$dd = Mage::getModel('ddate/ddate_store')->load($incrementId,'increment_id');
				if(count($dd)){
						$dd2 = Mage::getModel('ddate/ddate')->load($dd->getDdateId());
						$date = $dd2->getDdate();
						$time = Mage::getModel('ddate/dtime')->load($dd2->getDtime())->getInterval();
						$exptime = explode('-',$time);
						$time = $exptime[0];
						
						$adit = Mage::getModel('sales2/sales2');
						$adit->setId(null);
						$adit->setOrderId($entityId);
						$adit->setShippingDate($date);
						$adit->setShippingTime2($time);
						$adit->save();
				
				}
        return $this;
    }
}