<?php



class Hackathon_MageTrashApp_Block_Adminhtml_System_Config_Form_Fieldset_Modules_Rewind
    extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    protected $_dummyElement;
    protected $_fieldRenderer;
    protected $_values;


    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = $this->_getHeaderHtml($element);

        $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());

        $dispatchResult = new Varien_Object($modules);
        Mage::dispatchEvent(
            'magetrashapp_system_config_magetrashapp_manage_extns_render_before',
            array('modules' => $dispatchResult)
        );
        $modules = $dispatchResult->toArray();

        sort($modules);

        foreach ($modules as $moduleName) {
            $moduleStatus = Mage::getConfig()->getModuleConfig($moduleName)->is('active', 'true');

            if ($moduleName==='Mage_Adminhtml'  ||$moduleName==='Hackathon_MageTrashApp'
                || stripos($moduleName,'Mage_') !== false) {
                continue;
            }
            $html.= $this->_getFieldHtml($element, $moduleName,$moduleStatus);
        }
        $html .= $this->_getFooterHtml($element);
        Mage::log('html');
        return $html;
    }

    protected function _getDummyElement()
    {
        if (empty($this->_dummyElement)) {
            $this->_dummyElement = new Varien_Object(array('show_in_default'=>1, 'show_in_website'=>1));
        }
        return $this->_dummyElement;
    }

    protected function _getFieldRenderer()
    {
        if (empty($this->_fieldRenderer)) {
            $this->_fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
        }
        return $this->_fieldRenderer;
    }


    /**
     * @param $moduleName
     * @return array
     */
    protected function _getValues($moduleName)
    {
        //TODO: get working for all extensions rather than just PremiumMatrixrate

        $nameSpaceModule = str_replace('_', '/', $moduleName);

        $resName = Mage::helper('magetrashapp')->getResourceName($moduleName);

        $magentoRoot = dirname(Mage::getRoot());
        $uninstallscript = $magentoRoot . '/app/code/community' . DS . $nameSpaceModule . DS . 'sql' . DS . $resName . DS. '*.*';
        $uninstallscript2 = $magentoRoot . '/app/code/community' . DS . $nameSpaceModule . DS . 'sql' . DS . $resName . DS;

        $blah = array();
        $i = 1;
        foreach(glob($uninstallscript) as $filename){
            $baseName = explode("-",basename($filename));

            foreach ($baseName as $part) {
                if (strpos($part, ".php")) {
                    $part = str_replace('.php', '', $part);
                    $value = $part;
                }
            }

            $filename = str_replace($uninstallscript2, "", $filename);
            $blah[] = array('label'=>Mage::helper('adminhtml')->__('Rewind core_resource: ' . $value),    'value'=>'1_' . $value);
            $i++;
        }
        $blah = array_reverse($blah);
        array_unshift($blah, array('label'=>Mage::helper('adminhtml')->__('Delete core_resource'),    'value'=>0));
        array_unshift($blah, array('label'=>Mage::helper('adminhtml')->__('Do nothing'),    'value'=>2));
        //$lastestSQL = array_pop($blah);
        //array_unshift($blah, $lastestSQL);

        //if (empty($this->_values)) { TODO: do you need this??
            $this->_values = $blah;
        Mage::log($blah);
        //}

        return $this->_values;
    }


    protected function _getFieldHtml($fieldset, $moduleName,$moduleStatus)
    {

        $e = $this->_getDummyElement();

        $field = $fieldset->addField($moduleName . '1', 'select', //TODO
            array(
                'name'          => 'groups[rewind_extns][fields]['.$moduleName.'][value]',
                'label'         => $moduleName,
                'value'         => 2,
                'values'        => $this->_getValues($moduleName),
                'inherit'       => true,
                'can_use_default_value' => $this->getForm()->canUseDefaultValue($e),
                'can_use_website_value' => $this->getForm()->canUseWebsiteValue($e),
            ))->setRenderer($this->_getFieldRenderer());

        return $field->toHtml();
    }
}
