<?php
/**
 * WDCA - Sweet Tooth Instore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the SWEET TOOTH (TM) INSTORE
 * License, which extends the Open Software License (OSL 3.0).
 * The Sweet Tooth Instore License is available at this URL: 
 * http://www.sweettoothrewards.com/instore/sweet_tooth_instore_license.txt
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
 * provided Sweet Tooth Instore License. 
 * Upon discovery of modified code in the process of support, the Licensee 
 * is still held accountable for any and all billable time WDCA spent 
 * during the support process.
 * WDCA does not guarantee compatibility with any other framework extension. 
 * WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension.
 * If you did not receive a copy of the license, please send an email to 
 * support@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy 
 * immediately.
 * 
 * @category   [TBT]
 * @package    [TBT_Rewardsinstore]
 * @copyright  Copyright (c) 2011 Sweet Tooth (http://www.sweettoothrewards.com)
 * @license    http://www.sweettoothrewards.com/instore/sweet_tooth_instore_license.txt
 */

/**
 * @category   TBT
 * @package    TBT_Rewardsinstore
 * @author     Sweet Tooth Instore Team <support@wdca.ca>
 */
class TBT_Rewardsinstore_Model_Mysql4_Quote_Collection extends Mage_Sales_Model_Mysql4_Quote_Collection
{
    protected function _construct()
    {
        $this->_init('rewardsinstore/quote');
        $this->_setIdFieldName('quote_id');
    }
    
    /**
     * Init collection select
     *
     * @return TBT_Rewardsinstore_Model_Mysql4_Quote_Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        
        // join the base quotes' data to allow us to treat these as full-fledged quotes
        $base = Mage::getModel('sales/quote')->getResource();
        $this->getSelect()->joinLeft($base->getMainTable(), "{$base->getMainTable()}.{$base->getIdFieldName()} = main_table.{$this->getIdFieldName()}");
        
        return $this;
    }
    
    public function orderByCreatedAt($dir = 'desc')
    {
        $this->setOrder('created_at', $dir);
        return $this;
    }
    
    /**
     * Join Customer Name (concat)
     *
     * @return TBT_Rewardsinstore_Model_Mysql4_Quote_Collection
     */
    public function joinCustomerName($alias = 'name')
    {
	$sales_quote = Mage::getResourceModel('sales/quote');	
        $this->getSelect()->columns(array($alias => "CONCAT({$sales_quote->getMainTable()}.customer_firstname, ' ', {$sales_quote->getMainTable()}.customer_lastname)"));
        return $this;
    }
    
    // TODO: change this to sumPoints
    public function addPointsSum($alias = 'points_count')
    {
	$rewards_tr_ref = Mage::getResourceModel('rewards/transfer_reference');
	$rewards_tr = Mage::getResourceModel('rewards/transfer');
	
        $this->getSelect()
            ->joinLeft($rewards_tr_ref->getMainTable(),
                "main_table.quote_id = {$rewards_tr_ref->getMainTable()}.reference_id",
                array('reference_type', 'rewards_transfer_id'))
            ->joinLeft($rewards_tr->getMainTable(),
                "{$rewards_tr_ref->getMainTable()}.rewards_transfer_id = {$rewards_tr->getMainTable()}.rewards_transfer_id",
                array($alias => "SUM({$rewards_tr->getMainTable()}.quantity)", 'reason_id', 'issued_by','quantity'))
            ->where("{$rewards_tr_ref->getMainTable()}.reference_type = ?", TBT_Rewardsinstore_Model_Transfer_Reference_Quote::REFERENCE_TYPE_ID)
            ->where("{$rewards_tr->getMainTable()}.reason_id = ?", 1)
            ->group('main_table.quote_id');
        
        //$this->getSelect()->columns(array($alias => "CONCAT('50', '')"));
        return $this;
    }
}
