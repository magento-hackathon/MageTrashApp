<?php
/**
 * Ffuenf_MageTrashApp extension.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category   Ffuenf
 *
 * @author     Achim Rosenhagen <a.rosenhagen@ffuenf.de>
 * @copyright  Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_MageTrashApp_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup
{
    /**
     * @param string $fileName
     * @param string $resourceName
     */
    public function runUninstallSql($fileName, $resourceName) {
        Mage::getSingleton('adminhtml/session')->addNotice('Invoking uninstall file for resource' . $resourceName);
        $connection = Mage::getSingleton('core/resource')->getConnection($resourceName);
        $connection->disallowDdlCache();
        try {
            // run sql uninstall php
            $result = include $fileName;
            // remove core_resource
            if ($result) {
                Mage::getSingleton('adminhtml/session')->addNotice('Removing core resource ' . $resourceName);
                $this->deleteTableRow('core/resource', 'code', $resourceName);
            }
        } catch (Exception $e) {
            $result = false;
            Mage::log($e);
            Mage::getSingleton('adminhtml/session')->addWarning('Running uninstall failed for resource ' . $resourceName);
        }
        $connection->allowDdlCache();
        return $result;
    }
}