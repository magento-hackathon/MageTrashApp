<?php
class Hackthon_MageTrashApp_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function uninstallModule($moduleName)
	{
		$configModule = Mage::getConfig()->getModuleConfig($moduleName);
		if ($configModule->is('active', true)) {
			Mage::throwException($this->__('The module %s must be disabled before to uninstall.', $moduleName));
			return;
		}
		
		// Check the dependencies first and allow the rest of the process otherwise block it
		$dependencies = $this->chechDependencies($moduleName);
		if (count($dependencies) > 0) {
			Mage::throwException(
				$this->__('The module %s has dependencies with the module(s) %s. Please fix that before to remove this module.', $moduleName, implode(',',	$dependencies))
			);
			return;
		}
		
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
	
	/**
	 * 
	 * @param string $moduleName
	 * @return boolean | array
	 */
	protected function checkDependencies ($moduleName)
	{
		$moduleDepends = array();
		$modules = (array)Mage::getConfig()->getNode('modules')->children();
		foreach ($modules as $parentName => $module) {
			if ($module->depends) {
				$depends = (array) $module->depends;
				foreach ($depends as $name => $depend) {
					if ($name == $moduleName) {
						$moduleDepends[] = $parentName;
					}
				}
			}
		}
		
		return $moduleDepends;
	}

    /**
     * Activate/Deactivate a Magento module
     *
     * @param  string $name
     * @return string
     */
    public function deactivateModule($name)
    {
        $isDeactivationPossible = true;
        foreach (Mage::getConfig()->getNode('modules')->children() as $moduleName => $item) {
            if ($moduleName == $name) {
                continue;
            }
            if ($item->depends) {
                $depends = array();
                foreach ($item->depends->children() as $depend) {
                    if ($depend->getName() == $name) {
                        if ((string) Mage::getConfig()->getModuleConfig($moduleName)->is('active', 'true')) {
                            $isDeactivationPossible = false;
                        }
                    }
                }
            }
        }

        if ($isDeactivationPossible) {
            $status = '';
            $xmlPath = Mage::getBaseDir() . DS . 'app' . DS . 'etc' . DS . 'modules' . DS . $name .'.xml';
            if (file_exists($xmlPath)) {
                $xmlObj = new Varien_Simplexml_Config($xmlPath);

                $currentValue = (string) $xmlObj->getNode('modules/'.$name.'/active');
                if ($currentValue == 'true') {
                    $value = false;
                } else {
                    $value = true;
                }

                $xmlObj->setNode(
                    'modules/'.$name.'/active',
                    $value ? 'true' : 'false'
                );

                if (is_writable($xmlPath)) {
                    $xmlData = $xmlObj->getNode()->asNiceXml();
                    @file_put_contents($xmlPath, $xmlData);
                    Mage::app()->getCacheInstance()->clean();
                    if ($value) {
                        $status = $this->__('The module "%s" has been successfully activated.', $name);
                    } else {
                        $status = $this->__('The module "%s" has been successfully deactivated.', $name);
                    }
                } else {
                    $status = $this->__('File %s is not writable.', $xmlPath);
                }
            } else {
                $status = $this->__(
                    'Module %s is probably not installed. File %s does not exist.',
                    $name,
                    $xmlPath
                );
            }
        } else {
            $status = $this->__('Module can\'t be deactivated because it is a dependency of another module which is still active.');
        }

        return $status;
    }
}