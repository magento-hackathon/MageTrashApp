<?php
/**
 * Ffuenf_MageTrashApp extension.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category   Ffuenf
 *
 * @author     Achim Rosenhagen <a.rosenhagen@ffuenf.de>
 * @copyright  Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_MageTrashApp_Model_Resource_Resource extends Mage_Core_Model_Resource_Resource
{
    /**
     * Delete core_resource entry
     *
     * @param $resName
     * @param $version
     * @return int
     */
    public function deleteDbVersion($resName, $version)
    {
        $dbModuleInfo = array(
            'code'    => $resName,
            'version' => $version,
        );
        if ($this->getDbVersion($resName)) {
            self::$_versions[$resName] = $resName;
            return $this->_getWriteAdapter()->delete($this->getMainTable(),
            array('code = ?' => $resName));
        } else {
            self::$_versions[$resName] = $version;
            return $this->_getWriteAdapter()->insert($this->getMainTable(), $dbModuleInfo);
        }
    }
}