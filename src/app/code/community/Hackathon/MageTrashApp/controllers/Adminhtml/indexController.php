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
	
	
	public function activateModule ()
	{
		
	}
	
	public function deactivateModule ()
	{
        
	}
}