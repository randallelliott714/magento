<?php
/**
 * OpenMage
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_Admin
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (http://www.magento.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * User acl role
 *
 * @category   Mage
 * @package    Mage_Admin
 * @author     Magento Core Team <core@magentocommerce.com>
 *
 * @method Mage_Admin_Model_Resource_Acl_Role _getResource()
 * @method Mage_Admin_Model_Resource_Acl_Role getResource()
 * @method int getParentId()
 * @method Mage_Admin_Model_Acl_Role setParentId(int $value)
 * @method int getTreeLevel()
 * @method Mage_Admin_Model_Acl_Role setTreeLevel(int $value)
 * @method int getSortOrder()
 * @method Mage_Admin_Model_Acl_Role setSortOrder(int $value)
 * @method string getRoleType()
 * @method Mage_Admin_Model_Acl_Role setRoleType(string $value)
 * @method int getUserId()
 * @method Mage_Admin_Model_Acl_Role setUserId(int $value)
 * @method string getRoleName()
 * @method Mage_Admin_Model_Acl_Role setRoleName(string $value)
 */
class Mage_Admin_Model_Acl_Role extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('admin/acl_role');
    }
}
