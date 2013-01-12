<?php

class Hackathon_MageTrashApp_Model_Uninstall extends Mage_Core_Model_Abstract
{
	public function uninstallSqlCommand ($moduleName)
	{
		// Get the file uninstall.php of the sql/xyz_setup/ folder
	
	}
	
	public function processUninstallPackage($moduleName)
	{
		// Remove the code from different codePool
		$config = Mage::getConfig();
		$configModule = $config->getModuleConfig($moduleName);
	
		if ($configModule->is('active', true)) {
			Mage::throwException($this->__('The module %s must be disabled before to uninstall.', $moduleName));
			return;
		}
	
		Mage::getConfig()->getBaseDir('app_dir');
	
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