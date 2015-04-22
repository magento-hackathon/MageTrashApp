<?php



class Hackathon_MageTrashApp_Block_Adminhtml_System_Config_Form_Fieldset_Modules_Rewind
    extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    protected $_dummyElement;
    protected $_fieldRenderer;


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
            if ($moduleName==='Mage_Adminhtml'  || $moduleName==='Hackathon_MageTrashApp'
                || stripos($moduleName,'Mage_') !== false) {
                continue;
            }

            $resName = Mage::helper('magetrashapp')->getResourceName($moduleName);
            if($resName===null) continue;
            $number = Mage::getResourceSingleton('core/resource')->getDbVersion($resName);
            if (!$resName || $resName == $number) {
                continue;
            }

            $html.= $this->_getFieldHtml($element, $moduleName);
        }
        $html .= $this->_getFooterHtml($element);
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
        $nameSpaceModule = str_replace('_', '/', $moduleName);
        $resName = Mage::helper('magetrashapp')->getResourceName($moduleName);
        $community = Mage::getBaseDir('code') . DS . 'community' . DS;

        $sqlScriptPath = $community . $nameSpaceModule . DS . 'sql' . DS . $resName . DS. '*.*';

        $valuesArray = array(
            array('label'=>Mage::helper('adminhtml')->__('Do nothing'),              'value'=>2),
            array('label'=>Mage::helper('adminhtml')->__('Delete core_resource'),    'value'=>0)
        );

        // Loop through all sql files and create a value for each
        foreach(glob($sqlScriptPath) as $filename){
            $filename = explode("-",basename($filename));

            foreach ($filename as $part) {
                if (strpos($part, ".php")) {
                    $part = str_replace('.php', '', $part);
                    $number = $part;
                }
            }

            $sqlVersionsArray[] = array('label'=>Mage::helper('adminhtml')->__(
                'Rewind core_resource: ' . $number), 'value'=>'1_' . $number
            );
        }

        if (!empty($sqlVersionsArray)) {
            $valuesArray = array_merge($valuesArray, array_reverse($sqlVersionsArray));
        }

        return $valuesArray;
    }


    protected function _getFieldHtml($fieldset, $moduleName)
    {

        $e = $this->_getDummyElement();

        $field = $fieldset->addField($moduleName . '_Rewind', 'select',
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
