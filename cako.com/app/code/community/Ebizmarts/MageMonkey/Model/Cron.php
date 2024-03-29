<?php

/**
 * CRON jobs processor
 *
 * @category   Ebizmarts
 * @package    Ebizmarts_MageMonkey
 * @author     Ebizmarts Team <info@ebizmarts.com>
 */
class Ebizmarts_MageMonkey_Model_Cron {

    /**
     * Limit var for SQL queries
     *
     * @var integer
     * @access protected
     */
    protected $_limit = 20000;

    /**
     * Current Magento store
     *
     * @var Mage_Core_Model_Store
     * @access protected
     */
    protected $_store;

    /**
     * Process scheduled IMPORT tasks
     *
     * @return void
     */
    public function processImportJobs() {
        $job = $this->_getJob('Import');
        if (is_null($job)) {
            return $this;
        }

        //Update total count on first run
        $setcount = FALSE;
        if (!$job->getTotalCount()) {
            $setcount = TRUE;
        }

        if (!$job->getStartedAt()) {
            $job->setStartedAt(Mage::getModel('core/date')->gmtDate())->save();
        }

        foreach ($job->lists() as $listId) {

            $toImport = array();

            $store = $this->_helper()->getStoreByList($listId);
            $websiteId = Mage::app()->getStore($store)->getWebsiteId();
            $this->_store = Mage::app()->getStore($store);

            $exportapi = Mage::getModel('monkey/api', array('store' => $store, '_export_' => TRUE));
            $api = Mage::getModel('monkey/api', array('store' => $store));

            $mergevars = $api->listMergeVars($listId);

            foreach ($job->statuses() as $status) {

                $members = $exportapi->listExport($listId, $status, NULL, $job->getSince());

                if (is_null($exportapi->errorCode) && $members) {
                    if (!isset($toImport[$status])) {
                        $toImport [$status] = array();
                    }
                    $mdata = $this->_helper('export')->parseMembers($members, $mergevars, $store);

                    $toImport[$status] = array_merge($toImport[$status], $mdata);

                    if ($setcount === TRUE) {
                        $job->setTotalCount((count($toImport[$status]) + (int) $job->getTotalCount()))->save();
                    }
                }
            }

            if (count($toImport) > 0) {

                $job->setStatus('running')
                        ->save();

                foreach ($toImport as $type => $emails) {

                    foreach ($emails as $data) {

                        //Run: subscribed, unsubscribed, cleaned or updated method
                        $this->{$type}($data, $websiteId, (bool) $job->getCreateCustomer());

                        $job->setProcessedCount(((int) $job->getProcessedCount() + 1))
                                ->save();
                    }
                }

                $job->setStatus('finished')
                        ->save();
            }
        }
    }

    /**
     * Return subscriber object with basic attribues
     *
     * @param string $email
     * @param string $status OPTIONAL
     * @return Mage_Newsletter_Model_Subscriber
     */
    protected function _getSubscriberObject($email, $status = Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED) {
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
        $subscriber->setImportMode(TRUE)->setBulksync(TRUE);

        if (!$subscriber->getId()) {
            $subscriber->setStoreId($this->_store->getId())
                    ->setSubscriberConfirmCode(Mage::getModel('newsletter/subscriber')->randomSequence())
                    ->setEmail($email);
        }

        $subscriber->setStatus($status);

        return $subscriber;
    }

    /**
     * Process <subscribed> data list when importing members
     *
     * @param array $member
     * @param integer $websiteId OPTIONAL
     * @param bool $createCustomer
     * @return void
     */
    protected function subscribed($member, $websiteId = null, $createCustomer = FALSE) {

        $subscriber = $this->_getSubscriberObject($member['email']);

        if ($createCustomer) {

            $customer = Mage::getModel('customer/customer')->setWebsiteId($websiteId)
                    ->loadByEmail($member['email']);

            //Create customer if not exists, and subscribe
            if (is_null($customer->getId())) {
                $customer = $this->_helper()->createCustomerAccount($member, $websiteId);
            }

            $subscriber
                    ->setCustomerId($customer->getId())
                    ->save();
        } else {

            //Just subscribe email
            $subscriber->save();
        }
    }

    /**
     * Process <updated> data list when importing members
     *
     * @param array $member
     * @param integer $websiteId OPTIONAL
     * @param bool $createCustomer
     * @return void
     */
    protected function updated($member, $websiteId = null, $createCustomer = FALSE) {
        //TODO
    }

    /**
     * Process <unsubscribed> data list when importing members
     *
     * @param array $member
     * @param integer $websiteId OPTIONAL
     * @param bool $createCustomer
     * @return void
     */
    protected function unsubscribed($member, $websiteId = null, $createCustomer = FALSE) {
        $this->_getSubscriberObject($member['email'], Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED)
                ->save();
    }

    /**
     * Process <cleaned> data list when importing members
     *
     * @param array $member
     * @param integer $websiteId OPTIONAL
     * @param bool $createCustomer
     * @return void
     */
    protected function cleaned($member, $websiteId = null, $createCustomer = FALSE) {
        return $this->unsubscribed($member, $websiteId, $createCustomer);
    }

    /**
     * Process EXPORT tasks
     *
     * @return Ebizmarts_MageMonkey_Model_Cron
     */
    public function processExportJobs() {
        Mage::log(__FILE__ . '::' . __LINE__ . " (" . date('d-M-Y H:i:s') . ')', null, 'monkey-log.txt');
        $this->_limit = (int) Mage::getStoreConfig("monkey/general/cron_export");
        $job = $this->_getJob('Export');
        if (is_null($job)) {
            return $this;
        }

        $collection = $this->_getEntityModel($job->getDataSourceEntity());

        if (!$job->getStartedAt()) {
            $job->setStartedAt(Mage::getModel('core/date')->gmtDate())->save();
        }

        $collection->setPageSize($this->_limit);

        //Condition for chunk batch
        if ($job->getLastProcessedId()) {
            $collection->addFieldToFilter($this->_getId($job->getDataSourceEntity()), array('gt' => (int) $job->getLastProcessedId()));
        }

        //Filter by STORE
        $jobStoreId = (int) $job->getStoreId();
        if ($jobStoreId) {
            $collection->addFieldToFilter('store_id', $jobStoreId);
        }

        if ($job->getDataSourceEntity() == 'newsletter_subscriber'):
            $collection->addFieldToFilter('subscriber_status', Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED);
        endif;

        $collection->load();

        //Update total count on first run
        if (!$job->getTotalCount()) {
            $allRows = $collection->getSize();
            $job->setTotalCount($allRows)->save();
        }

        $batch = array();

        Mage::log($job->lists(), null, 'monkey-log.txt');
        foreach ($job->lists() as $listId) {
            $store = $this->_helper()->getStoreByList($listId);
            $api = Mage::getSingleton('monkey/api', array('store' => $store));

            $processedCount = 0;
            foreach ($collection as $item) {
                $processedCount += 1;
                $batch [] = $this->_helper()->getMergeVars($item, TRUE);
            }

            if (count($batch) > 0) {

                $job->setStatus('chunk_running')
                        ->setUpdatedAt($this->_dbDate())
                        ->save();

                $vals = $api->listBatchSubscribe($listId, $batch, FALSE, TRUE, FALSE);

                if (is_null($api->errorCode)) {

                    $lastId = $collection->getLastItem()->getId();
                    $job->setLastProcessedId($lastId);
                    $job->setProcessedCount(( $processedCount + $job->getProcessedCount()));

                    /* if( $processedCount < $this->_limit ){
                      $job->setStatus('finished');
                      } */

                    $job
                            ->setUpdatedAt($this->_dbDate())
                            ->save();
                } else {

                    //TODO: Do something to handle errors

                    /* echo "Batch Subscribe failed!\n";
                      echo "code:".$api->errorCode."\n";
                      echo "msg :".$api->errorMessage."\n";
                      die; */
                    /* echo "added:   ".$vals['add_count']."\n";
                      echo "updated: ".$vals['update_count']."\n";
                      echo "errors:  ".$vals['error_count']."\n";
                      foreach($vals['errors'] as $val){
                      echo $val['email_address']. " failed\n";
                      echo "code:".$val['code']."\n";
                      echo "msg :".$val['message']."\n";
                      }
                      die; */
                    Mage::log("Batch Subscribe failed", null, 'monkey-log.txt');
//                    Mage::log("code: " . $api->errorCode, null, 'monkey-log.txt');
//                    Mage::log("msg: " . $api->errorMessage, null, 'monkey-log.txt');
//                    Mage::log("added: " . $vals['add_count'], null, 'monkey-log.txt');
//                    Mage::log("updated: " . $vals['update_count'], null, 'monkey-log.txt');
//                    Mage::log("errors: " . $vals['error_count'], null, 'monkey-log.txt');
//                    foreach ($vals['errors'] as $val) {
//                        Mage::log($val['email_address'] . ' failed', null, 'monkey-log.txt');
//                        Mage::log("code: " . $val['code'], null, 'monkey-log.txt');
//                        Mage::log('msg: ' . $val['message'], null, 'monkey-log.txt');
//                    }
                    Mage::log($vals, null, 'monkey-log.txt');
                }
            } else {
                $job
                        ->setStatus('finished')
                        ->setUpdatedAt($this->_dbDate())
                        ->save();
            }
        }
        Mage::log(__FILE__ . '::' . __LINE__ . " (" . date('d-M-Y H:i:s') . ')', null, 'monkey-log.txt');
        return $this;
    }

    /**
     * Get collection object for given entity type
     *
     * @todo Add default Billing and Shipping address data
     * @param string $type
     * @return Mage_Newsletter_Model_Mysql4_Subscriber_Collection|Mage_Customer_Model_Entity_Customer_Collection
     */
    protected function _getEntityModel($type) {
        $model = null;

        switch ($type) {
            case 'newsletter_subscriber':
                $model = Mage::getResourceSingleton('newsletter/subscriber_collection')
                        //->showCustomerInfo(true)
                        ->addSubscriberTypeField()
                        ->showStoreInfo();
                break;
            case 'customer':

                //TODO: Add default Billing and Shipping address data

                $model = Mage::getResourceModel('customer/customer_collection')
                        ->addNameToSelect()
                        ->addAttributeToSelect('gender')
                        ->addAttributeToSelect('dob');
                break;
        }

        return $model;
    }

    /**
     * Return ID field name for given entity type
     *
     * @param string $type
     * @return string
     */
    protected function _getId($type) {
        $idFieldName = null;

        switch ($type) {
            case 'newsletter_subscriber':
                $idFieldName = 'subscriber_id';
                break;
            default:
                $idFieldName = 'entity_id';
        }

        return $idFieldName;
    }

    /**
     * Get HELPER instance
     *
     * @param string $which
     * @return object
     */
    protected function _helper($which = 'data') {
        return Mage::helper('monkey/' . $which);
    }

    /**
     * Return GMT date
     *
     * @return string
     */
    protected function _dbDate() {
        return Mage::getModel('core/date')->gmtDate();
    }

    /**
     * Get first job to process in queue
     *
     * @param string $entity
     * @return null|Ebizmarts_MageMonkey_Model_BulksyncExport|Ebizmarts_MageMonkey_Model_BulksyncImport
     */
    protected function _getJob($entity) {
        $job = Mage::getModel("monkey/bulksync{$entity}")
                ->getCollection()
                ->addFieldToFilter('status', array('IN' => array('idle', 'chunk_running')))
                ->addOrder('created_at', 'asc')
                ->load();
        if (!$job->getFirstItem()->getId()) {
            return null;
        }

        return $job->getFirstItem();
    }

}
