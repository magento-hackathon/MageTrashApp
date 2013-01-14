<?php

class Hackathon_MageTrashApp_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup
{

    public function runUninstallSql($fileName,$resourceName) {

        Mage::getSingleton('adminhtml/session')->addNotice('Invoking uninstall file for resource'.$resourceName);

        $connection = Mage::getSingleton('core/resource')->getConnection($resourceName);

        $connection->disallowDdlCache();

        try {
            // run sql uninstall php
            $result = include $fileName;
            // remove core_resource
            if ($result) {
                Mage::getSingleton('adminhtml/session')->
                    addNotice('Removing core resource '.$resourceName);
                $this->deleteTableRow('core/resource', 'code', $resourceName);
            }

        } catch (Exception $e) {
            $result = false;
            Mage::log($e);
            Mage::getSingleton('adminhtml/session')->
                addWarning('Running uninstall failed for resource '.$resourceName);
        }

        $connection->allowDdlCache();

        return $result;

    }

}