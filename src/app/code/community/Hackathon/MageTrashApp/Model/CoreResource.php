<?php

class Hackathon_MageTrashApp_Model_CoreResource extends Mage_Core_Model_Abstract{

    /**
     * Delete Core Resource for specified module
     *
     * @param $moduleName
     * @param $resName
     * @param $number
     */
    public function deleteCoreResource($moduleName, $resName, $number)
    {
        if (!$number) {
            Mage::getSingleton('adminhtml/session')->AddNotice('No CoreResource version found for:'. $moduleName);
        } else {
            Mage::register('isSecureArea', true);
            $resource = Mage::getResourceSingleton('magetrashapp/resource');
            $resource->deleteDbVersion($resName, $number);
            Mage::unregister('isSecureArea');

            if ($resource->getDbVersion($resName) == $resName) {
                Mage::getSingleton('adminhtml/session')->AddNotice('CoreResource Deleted for:'. $moduleName);
            }
        }
    }

    /**
     * Reset Core Resource to specified version
     *
     * @param $moduleName
     * @param $resName
     * @param $number
     */
    public function rewindCoreResource ($moduleName, $resName, $number)
    {
        Mage::register('isSecureArea', true);
        $resource = Mage::getResourceSingleton('core/resource');
        $resource->setDbVersion($resName, $number);
        Mage::unregister('isSecureArea');

        if ($resource->getDbVersion($resName) == $number) {
            Mage::getSingleton('adminhtml/session')->AddNotice($moduleName .
                ' CoreResource version rewound to: ' .$number);
        }
    }
}