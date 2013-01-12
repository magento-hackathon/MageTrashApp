<?php

class Hackathon_MageTrashApp_Model_Uninstall extends Mage_Core_Model_Abstract
{
	public function uninstallSqlCommand ($moduleName)
	{
		// Get the file uninstall.php of the sql/xyz_setup/ folder
	
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