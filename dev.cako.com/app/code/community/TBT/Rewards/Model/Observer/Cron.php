<?php

/**
 * WDCA - Sweet Tooth
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the WDCA SWEET TOOTH POINTS AND REWARDS 
 * License, which extends the Open Software License (OSL 3.0).
 * The Sweet Tooth License is available at this URL: 
 * http://www.wdca.ca/sweet_tooth/sweet_tooth_license.txt
 * The Open Software License is available at this URL: 
 * http://opensource.org/licenses/osl-3.0.php
 * 
 * DISCLAIMER
 * 
 * By adding to, editing, or in any way modifying this code, WDCA is 
 * not held liable for any inconsistencies or abnormalities in the 
 * behaviour of this code. 
 * By adding to, editing, or in any way modifying this code, the Licensee
 * terminates any agreement of support offered by WDCA, outlined in the 
 * provided Sweet Tooth License. 
 * Upon discovery of modified code in the process of support, the Licensee 
 * is still held accountable for any and all billable time WDCA spent 
 * during the support process.
 * WDCA does not guarantee compatibility with any other framework extension. 
 * WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension.
 * If you did not receive a copy of the license, please send an email to 
 * contact@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy 
 * immediately.
 * 
 * @category   [TBT]
 * @package    [TBT_Rewards]
 * @copyright  Copyright (c) 2009 Web Development Canada (http://www.wdca.ca)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @category   TBT
 * @package    TBT_Rewards
 * @author     WDCA Sweet Tooth Team <contact@wdca.ca>
 */
class TBT_Rewards_Model_Observer_Cron extends Varien_Object {
	/* TODO WDCA - Change the classname and path of this to suit the event being observed */
	
	public function __construct() {
	
	}
	
	/*
     * @warning running this function twice in one day could result in notifying the customer twice.  
     */
	
	public function checkPointsExpiry($observer) {
		Mage::getSingleton ( 'rewards/expiry' )->checkAllCustomers ();
		return $this;
	}
	
    public function checkPointsProbation($observer)
    {
        // get collection of all Pending-Time transfers
        $transfers = Mage::getModel('rewards/transfer')->getCollection()
            ->addFilter('status', TBT_Rewards_Model_Transfer_Status::STATUS_PENDING_TIME);
        foreach ($transfers as $transfer) {
            // check each transfer if it is time to vest
            if (time() >= strtotime($transfer->getEffectiveStart())) {
                // ask dependent modules if it is safe to vest the transfer (default to yes)
                $result = new Varien_Object(array(
                    'is_safe_to_approve' => true
                ));
                Mage::dispatchEvent('rewards_transfer_vestation', array(
                    'transfer' => $transfer,
                    'result'   => $result,
                ));
                
                // approve or cancel transfer, based on dependent modules' feedback
                if ($result->getIsSafeToApprove()) {
                    $transfer->setStatus(
                            $transfer->getStatus(),
                            TBT_Rewards_Model_Transfer_Status::STATUS_APPROVED)
                        ->save();
                } else {
                    $transfer->setStatus(
                            $transfer->getStatus(),
                            TBT_Rewards_Model_Transfer_Status::STATUS_CANCELLED)
                        ->save();
                }
            }
        }
        
        return $this;
    }
    
    public function cfu() {
        if(!Mage::getStoreConfigFlag('rewards/general/cfu')) {
            return $this;
        }
        
        Mage::helper ( 'rewards/loyalty' )->checkForUpdates ( );
        
        return $this;
    }
    
	public function cronTest($observer) {
		return $this;
	}

}