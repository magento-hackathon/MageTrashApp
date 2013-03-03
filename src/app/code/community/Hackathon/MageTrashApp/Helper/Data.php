<?php
class Hackathon_MageTrashApp_Helper_Data extends Mage_Core_Helper_Abstract
{
    const DISABLE = 0;
    const ENABLE = 1;
    const UNINSTALL = 2;

    const DELETE = 0;
    const REWIND = 1;

	public function uninstallModule($moduleName)
	{
        // deactivate the module
        $this->activateModule($moduleName,false);
		
		// Check the dependencies first and allow the rest of the process otherwise block it
		$dependencies = $this->checkDependencies($moduleName);
		if (count($dependencies) > 0) {
			Mage::throwException(
				$this->__('The module %s has dependencies with the module(s) %s. Please fix that before to remove this module.', $moduleName, implode(',',	$dependencies))
			);
			return;
		}

		/* @var $uninstallModel Hackathon_MageTrashApp_Model_Uninstall */
		$uninstallModel = Mage::getModel('magetrashapp/uninstall');
		
		// We need to trigger SQL uninstall scripts
		Mage::dispatchEvent('magetrashapp_before_sql_uninstall');
		$uninstallModel->uninstallSqlCommand($moduleName);
		Mage::dispatchEvent('magetrashapp_after_sql_uninstall');
		
		// We need to remove all package files based on uninstall.txt file or modman file
		Mage::dispatchEvent('magetrashapp_before_package_uninstall');
		$uninstallModel->processUninstallPackage($moduleName);
		Mage::dispatchEvent('magetrashapp_after_package_uninstall');
        Mage::getSingleton('adminhtml/session')->addSuccess($moduleName.' has been uninstalled.');

        // Do the cleanup of the config here because we need the old config until this point
        Mage::app()->getStore()->resetConfig();
    }
	
	/**
	 * 
	 * @param string $moduleName
	 * @return boolean | array
	 */
	protected function checkDependencies ($moduleName)
	{
		$moduleDepends = array();
		foreach (Mage::getConfig()->getNode('modules')->children() as $parentName => $module) {
		    if ($parentName == $moduleName) {
		        continue;
		    }
		    
			if ($module->depends) {
				foreach ($module->depends->children() as $name => $depend) {
					if ($name === $moduleName && (bool) Mage::getConfig()->getModuleConfig($moduleName)->is('active', 'true')) {
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
    public function activateModule($name,$activateFlag = true)
    {
        $isDeactivationPossible = true;
        if (count($this->checkDependencies($name)) > 0) {
            $isDeactivationPossible = false;
        }


        if ($isDeactivationPossible) {
            $status = '';
            $xmlPath = Mage::getBaseDir() . DS . 'app' . DS . 'etc' . DS . 'modules' . DS . $name .'.xml';
            if (file_exists($xmlPath)) {
                $xmlObj = new Varien_Simplexml_Config($xmlPath);

                $xmlObj->setNode(
                    'modules/'.$name.'/active',
                    $activateFlag ? 'true' : 'false'
                );

                if (is_writable($xmlPath)) {
                    $xmlData = $xmlObj->getNode()->asNiceXml();
                    @file_put_contents($xmlPath, $xmlData);
                    if ($activateFlag) {
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
    
    public function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * Delete Core Resource for specified module
     *
     * @param $moduleName
     */
    public function deleteCoreResource($moduleName)
    {
        $resName = $this->getResourceName($moduleName);
        $number = Mage::getResourceSingleton('core/resource')->getDbVersion($resName);

        Mage::getModel('magetrashapp/coreresource')->deleteCoreResource($moduleName, $resName, $number);
    }

    /**
     * Rewind Core Resource for specified module
     *
     * @param $moduleName
     * @param $number
     */
    public function rewindCoreResource ($moduleName, $number)
    {
        $resName = Mage::helper('magetrashapp')->getResourceName($moduleName);

        Mage::getModel('magetrashapp/coreresource')->rewindCoreResource($moduleName, $resName, $number);
    }

    /**
     * Get resource name from config.xml node
     *
     * @param $moduleName
     * @return mixed
     */
    public function getResourceName($moduleName) {
        $config = Mage::app()->getConfig();
        $xmlPath = $config->getModuleDir('etc', $moduleName) . DS . 'config.xml';

        if (file_exists($xmlPath)) {
            $xmlObj = new Varien_Simplexml_Config($xmlPath);

            $resourceNode = $xmlObj->getNode('global/resources');
            if ($resourceNode) {
                $resourceNode = $resourceNode->asArray();
                reset($resourceNode);
                $resName = key($resourceNode);
                return $resName;
            }
        }
    }
}