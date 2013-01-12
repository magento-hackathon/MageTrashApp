<?php
class Hackathon_MageTrashApp_Helper_Data extends Mage_Core_Helper_Abstract
{

    const ENABLE = 0;
    const DISABLE = 1;
    const UNINSTALL = 2;

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
					if ($name === $moduleName) {
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

                $xmlObj->setNode(
                    'modules/'.$name.'/active',
                    $activateFlag ? 'true' : 'false'
                );

                if (is_writable($xmlPath)) {
                    $xmlData = $xmlObj->getNode()->asNiceXml();
                    @file_put_contents($xmlPath, $xmlData);
                    Mage::app()->getCacheInstance()->clean();
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
}