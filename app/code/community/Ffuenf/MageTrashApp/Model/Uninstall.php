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

class Ffuenf_MageTrashApp_Model_Uninstall extends Mage_Core_Model_Abstract
{
    /**
     * Run the uninstall sql script to remove everything from module in database
     * This uninstall script must be provided by the extension provider
     *
     * @param string $moduleName
     */
    public function uninstallSqlCommand($moduleName)
    {
        Mage::getSingleton('adminhtml/session')->AddNotice('Running SQL Unininstall for Module:' . $moduleName);
        $result = false;
        $resources = Mage::getConfig()->getNode('global/resources')->children();
        foreach ($resources as $resName => $resource) {
            if (!$resource->setup) {
                continue;
            }
            if (isset($resource->setup->module)) {
                $testModName = (string)$resource->setup->module;
                if ($testModName == $moduleName) {
                    $resourceName = $resName;
                }
            }
        }
        if (empty($resourceName)) {
            return $result;
        }
        $fileName = $this->_getUninstallSQLFile($moduleName, $resourceName);
        if (!is_null($fileName)) {
            $resource = new Ffuenf_MageTrashApp_Model_Resource_Setup($resourceName);
            $result = $resource->runUninstallSql($fileName, $resourceName);
        } else {
            Mage::getSingleton('adminhtml/session')->addNotice('Unable to find uninstall script for:' . $moduleName);
        }
        return $result;
    }

    /**
     * Gets the Uninstall file contents if present
     *
     * Lifted and modified from Mage_Core_Resource_Setup::_getAvailableDbFiles()
     *
     * @return null|string
     */
    protected function _getUninstallSQLFile($moduleName, $resourceName)
    {
        $filesDir = Mage::getModuleDir('sql', $moduleName) . DS . $resourceName;
        if (!is_dir($filesDir) || !is_readable($filesDir)) {
            return null;
        }
        $uninstallFile = null;
        $regExpDb = sprintf('#^.*%s\.(php|sql)$#i', 'uninstall');
        $handlerDir = dir($filesDir);
        while (false !== ($file = $handlerDir->read())) {
            $matches = array();
            if (preg_match($regExpDb, $file, $matches)) {
                $uninstallFile = $filesDir . DS . $file;
                break;
            }
        }
        $handlerDir->close();
        return $uninstallFile;
    }

    /**
     *  Options for uninstall are:
     * 1. Pear
     * 2. Using uninstall.sql and file as specified in config.xml
     * Format of the file must be modman???
     * @param string $moduleName
     */
    public function processUninstallPackage($moduleName)
    {
       $this->processFileBasedUninstall($moduleName);
    }

    /**
     * Attempts to uninstall Pear
     *
     * @param string $moduleName
     */
    protected function processPearUninstall($moduleName) {
        $command = 'uninstall';
        $params[] = 'community';
        $params[] = $moduleName;
        Mage_Connect_Command_Install::registerCommands(); // needed for init
        $pear = new Mage_Connect_Command_Install();
        $result = $pear->doUninstall($command, array(), $params);
    }

    /**
     * Attempts to uninstall Pear
     *
     * @param string $moduleName
     */
    protected function processFileBasedUninstall($moduleName)
    {
        $magentoRoot = dirname(Mage::getRoot());
        $config = Mage::app()->getConfig();
        $configModule = $config->getModuleConfig($moduleName);
        /* @var $configFile Mage_Core_Model_Config_Base */
        $configFile = Mage::getModel('core/config_base');
        /* @var $helper Ffuenf_MageTrashApp_Helper_Data */
        $helper = Mage::helper('magetrashapp');
        $etc = $config->getModuleDir('etc', $moduleName) . DS . 'config.xml';
        $configFile->loadFile($etc);
        $element = $configFile->getNode('uninstall');
        if (!empty($element) && !$element->filename) {
            $filename = $element->filename;
        } else {
            $filename = 'uninstall.txt';
        }
        $uninstallFile = $config->getModuleDir('etc', $moduleName) . DS . $filename;
        if (file_exists($uninstallFile)) {
            $handle = fopen($uninstallFile, 'r');
            while ($line = fgets($handle)) {
                $line = preg_replace('/\s+/', '%%%', $line);
                $lines = explode('%%%', $line);
                if (count($lines) > 2) { // modman file format, we take the second argument because it should be the path of the target installation
                    $pathsToDelete[] = $magentoRoot . DS . trim($lines[1], '/');
                } else {
                    $pathsToDelete[] = $magentoRoot . DS . trim($lines[0], '/');
                }
            }
            if (!feof($handle)) {
                $helper->__('A problem occured while trying to get access to the uninstall file.');
            }
            fclose($handle);
            foreach ($pathsToDelete as $dest) {
                if (file_exists($dest) && (is_file($dest) || is_link($dest))) {
                    unlink($dest);
                } else if (file_exists($dest)) {
                    $helper->rrmdir($dest);
                }
            }
            return true;
        }
        return false;
    }
}