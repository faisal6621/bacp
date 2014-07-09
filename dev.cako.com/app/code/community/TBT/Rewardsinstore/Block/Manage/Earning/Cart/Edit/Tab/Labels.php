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
class TBT_Rewardsinstore_Block_Manage_Earning_Cart_Edit_Tab_Labels extends TBT_Rewards_Block_Manage_Promo_Quote_Edit_Tab_Abstract
{
    /**
     * Prepare content for tab
     * 
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper ( 'salesrule' )->__ ( 'Labels' );
    }
    
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper ( 'salesrule' )->__ ( 'Labels' );
    }
    
    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }
    
    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }
    
    protected function _prepareForm()
    {
        $rule = $this->_getQuoteRule ();
        $form = new Varien_Data_Form ();
        $form->setHtmlIdPrefix ( 'rule_' );
        
        $fieldset = $form->addFieldset ( 'default_label_fieldset', array ('legend' => Mage::helper ( 'salesrule' )->__ ( 'Default Label' ) ) );
        $labels = $rule->getStoreLabels ();
        $fieldset->addField ( 'store_default_label', 'text', array ('name' => 'store_labels[0]', 'required' => false, 'label' => Mage::helper ( 'salesrule' )->__ ( 'Default Rule Label for All Store Views' ), 'value' => isset ( $labels [0] ) ? $labels [0] : '' ) );
        
        $fieldset = $form->addFieldset ( 'store_labels_fieldset', array ('legend' => Mage::helper ( 'salesrule' )->__ ( 'Store View Specific Labels' ), 'table_class' => 'form-list stores-tree' ) );
        foreach ( Mage::app ()->getWebsites () as $website ) {
            $fieldset->addField ( "w_{$website->getId()}_label", 'note', array ('label' => $website->getName (), 'fieldset_html_class' => 'website' ) );
            foreach ( $website->getGroups () as $group ) {
                $stores = $group->getStores ();
                if (count ( $stores ) == 0) {
                    continue;
                }
                $fieldset->addField ( "sg_{$group->getId()}_label", 'note', array ('label' => $group->getName (), 'fieldset_html_class' => 'store-group' ) );
                foreach ( $stores as $store ) {
                    $fieldset->addField ( "s_{$store->getId()}", 'text', array ('name' => 'store_labels[' . $store->getId () . ']', 'required' => false, 'label' => $store->getName (), 'value' => isset ( $labels [$store->getId ()] ) ? $labels [$store->getId ()] : '', 'fieldset_html_class' => 'store' ) );
                }
            }
        }
        
        if ($rule->isReadonly ()) {
            foreach ( $fieldset->getElements () as $element ) {
                $element->setReadonly ( true, true );
            }
        }
        
        $this->setForm ( $form );
        
        return parent::_prepareForm ();
    }
    
    /**
     * Fetches the currently open TBT_Rewards_Model_Salesrule_Rule.
     *
     * @return TBT_Rewards_Model_Salesrule_Rule
     */
    protected function _getQuoteRule()
    {
        return Mage::registry ( 'current_promo_quote_rule' );
    }
}
