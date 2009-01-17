<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Reports
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Report events resource model
 *
 * @category   Mage
 * @package    Mage_Reports
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Mage_Reports_Model_Mysql4_Event extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('reports/event', 'event_id');
    }

    public function updateCustomerType(Mage_Reports_Model_Event $model, $visitorId, $customerId, $types = array())
    {
        if ($types) {
            $this->_getWriteAdapter()->update($this->getMainTable(),
                array('subject_id'=>$customerId, 'subtype'=>0),
                array(
                    $this->_getWriteAdapter()->quoteInto('subject_id=?', $visitorId),
                    $this->_getWriteAdapter()->quoteInto('subtype=?', 1),
                    $this->_getWriteAdapter()->quoteInto('event_type_id IN(?)', $types)
                )
            );
        }
        return $this;
    }
}