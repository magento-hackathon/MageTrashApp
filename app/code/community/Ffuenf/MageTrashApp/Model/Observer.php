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

class Ffuenf_MageTrashApp_Model_Observer extends Mage_Core_Model_Abstract
{
    private $disableModules = array();

    public function saveConfig($observer)
    {
        $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        $dispatchResult = new Varien_Object($modules);
        $modules = $dispatchResult->toArray();
        foreach ($modules as $moduleName) {
            if ($moduleName === 'Mage_Adminhtml' || $moduleName === 'Ffuenf_MageTrashApp'
            || stripos($moduleName, 'Mage_') !== false) {
                continue;
            }
            $configFlag = Mage::getStoreConfig('magetrashapp/manage_extns/' . $moduleName);
            switch ($configFlag) {
                case Ffuenf_MageTrashApp_Helper_Data::ENABLE:
                    Mage::helper('magetrashapp')->activateModule($moduleName);
                    break;
                case Ffuenf_MageTrashApp_Helper_Data::DISABLE:
                    $this->disableModules[] = $moduleName;
                    Mage::helper('magetrashapp')->activateModule($moduleName, false);
                    break;
                case Ffuenf_MageTrashApp_Helper_Data::UNINSTALL:
                    Mage::helper('magetrashapp')->uninstallModule($moduleName);
                    break;
                default:
                    break;
            }
            $configFlag = Mage::getStoreConfig('magetrashapp/rewind_extns/' . $moduleName);
            if ($configFlag != 0) {
                $version = substr($configFlag, 2);
                $configFlag = $configFlag[0];
            } elseif (is_null($configFlag)) {
                continue;
            }
            switch ($configFlag) {
                case Ffuenf_MageTrashApp_Helper_Data::DELETE:
                    Mage::helper('magetrashapp')->deleteCoreResource($moduleName);
                    break;
                case Ffuenf_MageTrashApp_Helper_Data::REWIND:
                    Mage::helper('magetrashapp')->rewindCoreResource($moduleName, $version);
                    break;
                default:
                    break;
            }
        }
    }
}