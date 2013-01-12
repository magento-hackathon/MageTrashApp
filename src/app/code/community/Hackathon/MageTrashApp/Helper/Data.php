<?php
class Hackthon_MageTrashApp_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function uninstallModule($moduleName)
	{
		// Check the dependencies first and allow the rest of the process otherwise block it
		// @todo check modules they have dependencies with this module
		
		
		// We need to trigger SQL uninstall scripts
		Mage::dispatchEvent('magetrashapp_before_sql_uninstall');
		$this->_uninstallSqlCommand($moduleName);
		Mage::dispatchEvent('magetrashapp_after_sql_uninstall');
		
		// We need to remove all package files based on uninstall.txt file or modman file
		Mage::dispatchEvent('magetrashapp_before_package_uninstall');
		$this->_processUninstallPackage($moduleName);
		Mage::dispatchEvent('magetrashapp_after_package_uninstall');
		
	}
	
	protected function _uninstallSqlCommand ($moduleName)
	{
		// Get the file uninstall.php of the sql/xyz_setup/ folder
		
	} 
	
	protected function _processUninstallPackage($moduleName)
	{
		// Remove the code from different codePool
		
		
		
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