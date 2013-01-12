<?php
class Hackathon_MageTrashApp_Model_Observer extends Mage_Core_Model_Abstract {

        public function saveConfig($observer) {
            Mage::log('Damian');

            $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());

            $dispatchResult = new Varien_Object($modules);

            $modules = $dispatchResult->toArray();

            foreach ($modules as $moduleName) {
                if ($moduleName==='Mage_Adminhtml'  ||$moduleName==='Hackathon_MageTrashApp'
                    || stripos($moduleName,'Mage_') !== false) {
                    continue;
                }

                $configFlag = Mage::getStoreConfigFlag('magetrashapp/manage_extns/' . $moduleName);


                Mage::log($configFlag);

            }


        }


}