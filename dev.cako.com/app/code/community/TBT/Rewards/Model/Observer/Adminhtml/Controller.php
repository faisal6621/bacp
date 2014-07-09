<?php

try {
    include_once (Mage::getBaseDir('lib') . DS. 'SweetTooth' . DS . 'etc' . DS . 'SdkException.php');
    include_once (Mage::getBaseDir('lib') . DS. 'SweetTooth' . DS . 'etc' . DS . 'ApiException.php');
} catch (Exception $e) {
    die(__FILE__ . ": Wasn't able to load lib/SweetTooth.php.  Download rewardsplatformsdk.git and run the installer to symlink it.");  
}

class TBT_Rewards_Model_Observer_Adminhtml_Controller extends Varien_Object
{
    const TRANSFER_NOTIFIED = 'rewards/platform/milestones_transfer_notified';
    const TRANSFER_NOTIFICATIONS = 'rewards/platform/milestones_transfer_notifications';
    const DO_SHOW_NOTIFICATIONS = 'rewards/notifications/showUsageNotification';
    
    /**
     * Checks current account usage and, if the merchant has exceeded the next milestone
     * in the list of notifications, display a notification to the admin.
     * 
     * @return TBT_Rewards_Model_Observer_Adminhtml_Controller
     */
    public function createAccountUsageNotification($observer)
    {
        $doShowNotifications = Mage::getStoreConfig(self::DO_SHOW_NOTIFICATIONS);
        if (!$doShowNotifications) {
            return $this;
        }
        
        $notified_csv = Mage::getStoreConfig(self::TRANSFER_NOTIFIED);
        $notified = ($notified_csv) ? explode(",", $notified_csv) : array();
        
        $notifications_csv = Mage::getStoreConfig(self::TRANSFER_NOTIFICATIONS);
        $notifications = explode(",", $notifications_csv);
        
        try {
            $client = Mage::getSingleton('rewards/platform_instance');
            $account = $client->account()->get();
        } catch (SweetToothSdkException $sdkEx) {
            return $this;
        } catch (SweetToothApiException $apiEx) {
            Mage::getSingleton('core/session')->addWarning(
                Mage::helper('rewards')->__("Your Sweet Tooth API credentials seem to be invalid."));
            return $this;
        } catch (Exception $ex) {
            throw $ex;
        }
        
        if (isset($account['billing'])) {
            // check if we have already notified the admin of any account usage milestones being reached
            if (count($notified) > 0) {
                $latestNotification = $notified[count($notified) - 1];
                // get the latest milestone and, if their current usage is less, it must be the next month
                if ($account['billing']['percent'] < $latestNotification) {
                    // clear the list of notified since the month has reset
                    $notified = array();
                    Mage::getConfig()->saveConfig(self::TRANSFER_NOTIFIED, '');
                }
            }
            
            foreach ($notifications as $notification) {
                // don't notify the admin of a milestone if they've already been notified
                if (in_array($notification, $notified)) {
                    continue;
                }
                
                // if the admin has reached the next milestone, let them know
                if ($account['billing']['percent'] >= $notification) {
                    $this->_notify($account, $notification);
                    $notified[] = $notification;
                    $notified_csv = implode(",", $notified);
                    Mage::getConfig()->saveConfig(self::TRANSFER_NOTIFIED, $notified_csv);
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Creates an admin notification describing that an account usage milestone has been reached
     * 
     * @param array $account The account resource returned from the API
     * @param int $percent The percent usage milestone that the merchant has reached
     * @return TBT_Rewards_Model_Observer_Adminhtml_Controller
     */
    protected function _notify($account, $percent)
    {
        $transfers_used = $account['billing']['transfers_used'];
        $transfers_total = $account['billing']['transfers_total'];
        $plan = $account['billing']['plan'];
        $url = urldecode($account['login_url']);
        
        $severity = Mage_AdminNotification_Model_Inbox::SEVERITY_NOTICE;
        $date = date("c", time());
        $title = Mage::helper('rewards')->__("You have used %s%% of your Sweet Tooth subscription", $percent);
        $description = Mage::helper('rewards')->__("You have used %s points transfers out of %s total available transfers on your %s subscription plan.  You can upgrade at any time; go to the [billing_url]Billing section[/billing_url] of your Sweet Tooth Account.", $transfers_used, $transfers_total, $plan);
        $description = Mage::helper('tbtcommon/strings')->getTextWithLinks($description, 'billing_url',
            $url, array('target' => 'window'));
        
        Mage::getModel('adminnotification/inbox')
            ->setDateAdded($date)
            ->setSeverity($severity)
            ->setTitle($title)
            ->setDescription($description)
            ->setUrl('')
            ->save();
        
        return $this;
    }
}
