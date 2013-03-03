<?php
class Hackathon_MageTrashApp_Model_Observer extends Mage_Core_Model_Abstract {

    private $disableModules = array();

    public function saveConfig($observer) {

        $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());

        $dispatchResult = new Varien_Object($modules);

        $modules = $dispatchResult->toArray();

        foreach ($modules as $moduleName) {
            if ($moduleName==='Mage_Adminhtml'  ||$moduleName==='Hackathon_MageTrashApp'
                || stripos($moduleName,'Mage_') !== false) {
                continue;
            }

                $configFlag = Mage::getStoreConfig('magetrashapp/manage_extns/' . $moduleName);


            switch ($configFlag) {
                case Hackathon_MageTrashApp_Helper_Data::ENABLE:
                    Mage::helper('magetrashapp')->activateModule($moduleName);
                    break;
                case Hackathon_MageTrashApp_Helper_Data::DISABLE:
                    $this->disableModules[] = $moduleName;
                    Mage::helper('magetrashapp')->activateModule($moduleName, false);

                    break;
                case Hackathon_MageTrashApp_Helper_Data::UNINSTALL:
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
                case Hackathon_MageTrashApp_Helper_Data::DELETE:
                    Mage::helper('magetrashapp')->deleteCoreResource($moduleName);
                    break;
                case Hackathon_MageTrashApp_Helper_Data::REWIND:
                    Mage::helper('magetrashapp')->rewindCoreResource($moduleName, $version);
                    break;
                default:
                    break;

            }

        }


    }


}