<?php



class Hackathon_MageTrashApp_Adminhtml_Block_System_Config_Form_Fieldset_Modules_MageTrashApp
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
       // Mage::dispatchEvent(  //TODO: Dispatch event from MageTrashApp?
       //     'adminhtml_system_config_advanced_disableoutput_render_before',
       //     array('modules' => $dispatchResult)
       // );
        $modules = $dispatchResult->toArray();

        sort($modules);

        foreach ($modules as $moduleName) {
            if ($moduleName==='Mage_Adminhtml'  ||$moduleName==='Hackathon_MageTrashApp'
                || stripos($moduleName,'Mage_') !== false) {
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



    protected function _getValues()
    {
        if (empty($this->_values)) {
            $this->_values = array(
                array('label'=>Mage::helper('adminhtml')->__('Enable'),     'value'=>0),
                array('label'=>Mage::helper('adminhtml')->__('Disable'),    'value'=>1),
                array('label'=>Mage::helper('adminhtml')->__('Uninstall'),  'value'=>2),
            );
        }
        return $this->_values;
    }


    protected function _getFieldHtml($fieldset, $moduleName)
    {
        $configData = $this->getConfigData();
        $path = 'magetrashapp/modules_magetrashapp/'.$moduleName; //TODO: move as property of form
        if (isset($configData[$path])) {
            $data = $configData[$path];
            $inherit = false;
        } else {
            $data = (int)(string)$this->getForm()->getConfigRoot()->descend($path);
            $inherit = true;
        }

        $e = $this->_getDummyElement();

        $field = $fieldset->addField($moduleName, 'select',
            array(
                'name'          => 'groups[modules_magetrashapp][fields]['.$moduleName.'][value]',
                'label'         => $moduleName,
                'value'         => $data,
                'values'        => $this->_getValues(),
                'inherit'       => $inherit,
                'can_use_default_value' => $this->getForm()->canUseDefaultValue($e),
                'can_use_website_value' => $this->getForm()->canUseWebsiteValue($e),
            ))->setRenderer($this->_getFieldRenderer());

        return $field->toHtml();
    }
}
