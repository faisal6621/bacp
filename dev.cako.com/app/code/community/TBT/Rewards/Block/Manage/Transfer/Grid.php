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
 * Manage Transfer Grid
 *
 * @category   TBT
 * @package    TBT_Rewards
 * @author     WDCA Sweet Tooth Team <contact@wdca.ca>
 */
class TBT_Rewards_Block_Manage_Transfer_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	
	protected $collection = null;
	protected $columnsAreSet = false;
	
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'transfersGrid' );
		$this->setDefaultSort ( 'creation_ts' );
		$this->setDefaultDir ( 'DESC' );
		$this->setSaveParametersInSession ( true );
	}
	
	protected function _getStore() {
		$storeId = ( int ) $this->getRequest ()->getParam ( 'store', 0 );
		return Mage::app ()->getStore ( $storeId );
	}
	
	protected function _prepareCollection() {
		if ($this->collection == null) {
			$this->collection = Mage::getModel ( 'rewards/transfer' )->getCollection ();
		}
		
		$store = $this->_getStore ();
		if ($store->getId ()) {
			$this->collection->addStoreFilter ( $store );
		}
		
		$this->collection->selectFullCustomerName ( 'fullcustomername' );
		$this->collection->selectPointsCaption ( 'points' );
		$this->collection->excludeTransferReferences();
		
		$this->setCollection ( $this->collection );
		
		$this->addExportType ( '*/*/exportCsv', Mage::helper ( 'customer' )->__ ( 'CSV' ) );
		$this->addExportType ( '*/*/exportXml', Mage::helper ( 'customer' )->__ ( 'XML' ) );
		
		return parent::_prepareCollection ();
	}
	
	protected function _prepareColumns() {
		if ($this->columnsAreSet)
			return parent::_prepareColumns ();
		else
			$this->columnsAreSet = true;
		
		$this->addColumn ( 'rewards_transfer_id', array ('header' => Mage::helper ( 'rewards' )->__ ( 'ID' ), 'align' => 'right', 'width' => '36px', 'index' => 'rewards_transfer_id' ) );
		
		$this->addColumn ( 'creation_ts', array ('header' => Mage::helper ( 'rewards' )->__ ( 'Created Time' ), 'width' => '40px', 'type' => 'datetime', 'index' => 'creation_ts' ) );
		
		$this->addColumn ( 'points', array ('header' => Mage::helper ( 'rewards' )->__ ( 'Points' ), 'align' => 'left', 'width' => '70px', 'index' => 'points', 'filter_index' => "main_table.quantity" ) );
		
		/*
          $this->addColumn('customer_id', array(
          'header'    => Mage::helper('rewards')->__('Customer ID'),
          'align'     =>'left',
          'width'     => '100px',
          'index'     => 'customer_id',
          ));

         */
		
		$this->addColumn ( 'fullcustomername', array ('header' => Mage::helper ( 'rewards' )->__ ( 'Customer' ), 'align' => 'left', 'width' => '80px', 'index' => 'fullcustomername', 'filter_index' => "CONCAT(customer_firstname_table.value, ' ', customer_lastname_table.value)" ) );
		
		$reasons = Mage::getSingleton ( 'rewards/transfer_reason' )->getOptionArray ();
		if (count ( $reasons ) > 1) {
			$this->addColumn ( 'reason', array ('header' => Mage::helper ( 'rewards' )->__ ( 'Reason' ), 'align' => 'left', 'width' => '100px', 'index' => 'reason_id', 'type' => 'options', 'options' => $reasons ) );
		}
		
		$this->addColumn ( 'comments', array ('header' => Mage::helper ( 'rewards' )->__ ( 'Comments/Notes' ), 'width' => '250px', 'index' => 'comments' ) );
		
		$statuses = Mage::getSingleton ( 'rewards/transfer_status' )->getOptionArray ();
		$this->addColumn ( 'status', array ('header' => Mage::helper ( 'rewards' )->__ ( 'Status' ), 'align' => 'left', 'width' => '80px', 'index' => 'status', 'type' => 'options', 'options' => $statuses ) );
		$this->addColumn ( 'issued_by', array ('header' => Mage::helper ( 'rewards' )->__ ( 'Issued By' ), 'align' => 'left', 'width' => '80px', 'index' => 'issued_by', ) );
		$this->addColumn ( 'action', array ('header' => Mage::helper ( 'rewards' )->__ ( 'Action' ), 'width' => '100', 'type' => 'action', 'getter' => 'getId', 'actions' => array (array ('caption' => Mage::helper ( 'rewards' )->__ ( 'View' ), 'url' => array ('base' => '*/*/edit' ), 'field' => 'id' ) ), 'filter' => false, 'sortable' => false, 'index' => 'stores', 'is_system' => true ) );
		
		return parent::_prepareColumns ();
	}
	
	protected function _prepareMassaction() {
		$this->setMassactionIdField ( 'rewards_transfer_id' );
		$this->getMassactionBlock ()->setFormFieldName ( 'transfers' );
		
		$this->getMassactionBlock ()->addItem ( 'delete', array ('label' => Mage::helper ( 'rewards' )->__ ( 'Delete' ), 'url' => $this->getUrl ( '*/*/massDelete' ), 'confirm' => Mage::helper ( 'rewards' )->__ ( 'Are you sure?' ) ) );
		
		$statuses = Mage::getSingleton ( 'rewards/transfer_status' )->genSelectableStatuses ();
		
		$this->getMassactionBlock ()->addItem ( 'status', array ('label' => Mage::helper ( 'rewards' )->__ ( 'Change status' ), 'url' => $this->getUrl ( '*/*/massStatus', array ('_current' => true ) ), 'additional' => array ('visibility' => array ('name' => 'status', 'type' => 'select', 'class' => 'required-entry', 'label' => Mage::helper ( 'rewards' )->__ ( 'Status' ), 'values' => $statuses ) ) ) );
		
		return $this;
	}
	
	public function getRowUrl($row) {
		return $this->getUrl ( '*/*/edit', array ('id' => $row->getId () ) );
	}

}