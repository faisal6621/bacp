<?php

class Excellence_Customercron_Model_Customercron {

    public function exportCustomerList() {
        Mage::log(__FILE__ . ' #' . __LINE__ . ' :: cron entered');
//        $fromdate = date('Y-m-01 00:00:00', Mage::getModel('core/date')->timestamp(time()));
//        $todate = date('Y-m-t 23:59:59', Mage::getModel('core/date')->timestamp(time()));
        $fromdate = date('Y-m-01 00:00:00', Mage::getModel('core/date')->timestamp(strtotime('-1 month') + $this->getTimezoneOffset()));
        $todate = date('Y-m-t 23:59:59', Mage::getModel('core/date')->timestamp(strtotime('-1 month') + $this->getTimezoneOffset()));
        $csvData[] = array(
            'company' => 'Company Name',
            'firstname' => 'Shipping First Name',
            'lastname' => 'Shipping Last Name',
            'address' => 'Ship to Address',
            'phone' => 'Shipping Phone Number',
        );
//        $customers = Mage::getModel('customer/customer')->getCollection();
//        echo implode('<br/>', $customers->getColumnValues('entity_id'));
        $orders = Mage::getModel('sales/order')->getCollection()
//                ->addAttributeToFilter('customer_id', array(
//                    'in' => implode(',', $customers->getColumnValues('entity_id')))
//                )
                ->addAttributeToFilter('customer_is_guest', 0)
                ->addAttributeToFilter('created_at', array(
            'from' => $fromdate,
            'to' => $todate,
                ))
        ;
        Mage::log(__FILE__ . ' #' . __LINE__ . ' :: orders fetched - ' . $orders->getSize());
//        echo '<pre>';
//        echo $orders->getSelect().'<br/>';
//        echo implode('<br/>', $orders->getColumnValues('increment_id'));
        foreach ($orders as $order) {
            $shipping = $order->getShippingAddress();
            $street = $shipping->getStreet1() . ', ' . $shipping->getStreet2() . ', ';
//            print_r($shipping->getData());
            $address = str_replace(' ,', '', $street) . $shipping->getCity() . ', ' .
                    $shipping->getRegion() . ', ' .
                    Mage::getModel('directory/country')->loadByCode($shipping->getCountryId())->getName() . ', ' .
                    $shipping->getPostcode();
            $csvData[] = array(
                'company' => $shipping->getCompany(),
                'firstname' => $shipping->getFirstname(),
                'lastname' => $shipping->getLastname(),
                'address' => $address,
                'phone' => $shipping->getTelephone(),
            );
        }

        $dst = Mage::getBaseDir('media') . DS . 'products195';
        if (!file_exists($dst)) {
            if (!mkdir($dst, 0777, true)) {
                Mage::log('failed mkdir: ' . $dst);
//                $this->_getSession()->addError('failed mkdir: ' . $dst);
//                $this->_redirectReferer();
                Mage::log(__FILE__ . ' #' . __LINE__ . ' :: failure');
                return;
            }
        }
        $csvPath = $dst . DS . 'customer_list.csv';
        try {
            $poscsv = new Varien_File_Csv();
            $poscsv->saveData($csvPath, $csvData);
            $this->sendMail($csvPath);
            Mage::log(__FILE__ . ' #' . __LINE__ . ' :: success');
//            $this->_redirectUrl(Mage::getBaseUrl('media') . 'posimg/customer_list.csv');
        } catch (Exception $ex) {
            Mage::log(__FILE__ . ' #' . __LINE__ . ' :: failure');
            Mage::log($ex->getMessage());
        }
//        return;
    }

    public function getTimezoneOffset() {
        $timezone = Mage::app()->getStore()->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE);

        $timezone_offset = Mage::getModel('core/date')->calculateOffset($timezone);
        return $timezone_offset;
    }

    public function sendMail($file) {
        $body = "Please find the attachment <b>" . date('d-M-Y') . "_customer_list.csv</b>";
        $attachment = file_get_contents($file);
        $mail = new Zend_Mail('utf-8');
        $mail->addTo('albert@cako.com', 'Albert')
                ->addBcc('faisal@excellencetechnologies.in')
                ->addBcc('manish@excellencetechnologies.in')
                ->setFrom('albert@cako.com', 'Albert')
                ->setSubject('Google Fusion export: Customer List ' . date('d-M-Y'))
                ->setBodyHtml($body)
                ->createAttachment(
                        $attachment, Zend_Mime::TYPE_OCTETSTREAM, Zend_Mime::DISPOSITION_ATTACHMENT, Zend_Mime::ENCODING_BASE64, date('d-M-Y') . '_customer_list.csv'
                )
        ;
        try {
            $mail->send();
            Mage::log(__FILE__ . ' #' . __LINE__ . ' :: success');
        } catch (Exception $ex) {
            Mage::log(__FILE__ . ' #' . __LINE__ . ' :: failure');
            Mage::log($ex->getMessage());
        }
    }

}
