<?php

class Hackathon_MageTrashApp_Model_Uninstall extends Mage_Core_Model_Abstract
{
    /**
     * Run the uninstall sql script to remove everything from module in database
     * This uninstall script must be provided by the extension provider
     *
     * @param $moduleName
     */
    public function uninstallSqlCommand ($moduleName)
    {
        $result = false;

        $resources = Mage::getConfig()->getNode('global/resources')->children();
        foreach ($resources as $resName => $resource) {
            if (!$resource->setup) {
                continue;
            }

            if (isset($resource->setup->module)) {
                $testModName = (string)$resource->setup->module;
                if ($testModName==$moduleName) {
                    $resourceName = $resName;
                }
            }
        }

        if (empty($resourceName)) {
            return $result;
        }

        $fileName = $this->_getUninstallSQLFile($moduleName,$resourceName);

        if (!is_null($fileName) ) {

            $resource = new Hackathon_MageTrashApp_Model_Resource_Setup($resourceName);
            $result = $resource->runUninstallSql($fileName,$resourceName);

        }

        return $result;

    }

    /**
     * Gets the Uninstall file contents if present
     *
     * Lifted and modified from Mage_Core_Resource_Setup::_getAvailableDbFiles()
     *
     * @return bool
     */
    protected function _getUninstallSQLFile($moduleName,$resourceName) {


        $filesDir   = Mage::getModuleDir('sql', $moduleName) . DS . $resourceName;
        if (!is_dir($filesDir) || !is_readable($filesDir)) {
            return null;
        }

        $uninstallFile    = null;
        $regExpDb   = sprintf('#^.*%s\.(php|sql)$#i', 'uninstall');
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
     * @param $moduleName
     */
    public function processUninstallPackage($moduleName)
    {
        if (!$this->processPearUninstall($moduleName)) {
            $this->processFileBasedUninstall($moduleName);
        }
	}

    /**
     * Attempts to uninstall Pear
     *
     * @param $moduleName
     */
    protected function processPearUninstall($moduleName) {
        Mage::log("facebook foo");
        $command = 'uninstall';
        $params[] = 'community';
        $params[] = $moduleName;
        Mage_Connect_Command_Install::registerCommands();
        $pear = new Mage_Connect_Command_Install();
        //$result = $pear->doUninstall($command,array(),$params);

        $bla = 'dfdf';

    }

    /**
     * Attempts to uninstall Pear
     *
     * @param $moduleName
     */
    protected function processFileBasedUninstall($moduleName) {

        // Remove the code from different codePool

        $config = Mage::getConfig();
        //$configModule = $config->getModuleConfig($moduleName);

        //Mage::getConfig()->getBaseDir('app_dir');

        // 		$package = $cacheObj->getPackageObject($chanName, $package);
        // 		$contents = $package->getContents();

        // 		$targetPath = rtrim($configObj->magento_root, "\\/");
        // 		foreach ($contents as $file) {
        // 			$fileName = basename($file);
        // 			$filePath = dirname($file);
        // 			$dest = $targetPath . DIRECTORY_SEPARATOR . $filePath . DIRECTORY_SEPARATOR . $fileName;
        // 			if(@file_exists($dest)) {
        // 				@unlink($dest);
        // 				$this->removeEmptyDirectory(dirname($dest));
        // 			}
        // 		}

        // 		$destDir = $targetPath . DS . Mage_Connect_Package::PACKAGE_XML_DIR;
        // 		$destFile = $package->getReleaseFilename() . '.xml';
        // 		@unlink($destDir . DS . $destFile);
    }
}