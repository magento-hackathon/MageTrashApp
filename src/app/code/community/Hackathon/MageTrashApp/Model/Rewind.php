<?php

class Hackathon_MageTrashApp_Model_Rewind extends Mage_Core_Model_Abstract
{
    /**
     * Reset Core Resource to a give version.
     *
     * @param $moduleName
     * @param $coreResourceNumber
     */
    public function rewindCoreResource ($moduleName, $coreResourceNumber) //premiumrate_setup, //1.0.0
    {
        //TODO: IS THIS BEING USED??
        Mage::getSingleton('adminhtml/session')->AddNotice('Rewinding CoreResource version to:'
            .$coreResourceNumber .'for:'. $moduleName);

        Mage::register('isSecureArea', true); //temp
        $storeId=1; //temp

        $resource = Mage::getResourceSingleton('core/resource');
        $resource->setDbVersion($resName,$version);
    }
}
