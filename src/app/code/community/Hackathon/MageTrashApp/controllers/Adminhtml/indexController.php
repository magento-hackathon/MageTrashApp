<?php

class Hackathon_MageTrashApp_Adminhtml_indexController extends Mage_Core_Controller_Front_Action
{
	public function uninstallAction ()
	{
		$moduleName = $this->getRequest()->getParam('module_name');
		
		try {
			Mage::helper()->uninstallModule($moduleName);
		} catch (Exception $e) {
			
		}
	}
	
	
	public function activateModule ()//REQUIRED??
	{
		
	}
	
	public function deactivateModule ()//REQUIRED??
	{
        Mage::log('here?');
		Mage::helper()->rewindCoreResource('premiumrate_setup', '1.0.0');
	}
}