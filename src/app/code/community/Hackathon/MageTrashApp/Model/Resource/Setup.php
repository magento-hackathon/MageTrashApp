<?php

class Hackathon_MageTrashApp_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup
{

    public function runUninstallSql($fileName,$resourceName) {

        $connection = Mage::getSingleton('core/resource')->getConnection($resourceName);

        $connection->disallowDdlCache();

        try {
            // run sql uninstall php
            $result = include $fileName;
            // remove core_resource
            if ($result) {
                $this->deleteTableRow('core/resource', 'code', $resourceName);
            }

        } catch (Exception $e) {
            $result = false;
            Mage::log($e);
        }

        $connection->allowDdlCache();

        return $result;

    }

}