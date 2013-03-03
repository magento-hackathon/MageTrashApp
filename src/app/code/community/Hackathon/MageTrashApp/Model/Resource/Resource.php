<?php

class Hackathon_MageTrashApp_Model_Resource_Resource extends Mage_Core_Model_Resource_Resource
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