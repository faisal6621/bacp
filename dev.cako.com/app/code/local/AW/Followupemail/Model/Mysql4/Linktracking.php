<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento community edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento community edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Followupemail
 * @version    3.5.6
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Followupemail_Model_Mysql4_Linktracking extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('followupemail/linktracking', 'id');
    }

    public function getCustomerByEmail($email)
    {
        $db = $this->_getReadAdapter();

        $select = $db->select()
            ->from($this->getTable('customer/entity'), array('entity_id'))
            ->where('email=?', $email);

        return $db->fetchOne($select);
    }

}