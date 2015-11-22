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

class Ffuenf_MageTrashApp_Adminhtml_Block_System_Config_Form_Fieldset_Modules_MageTrashApp extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    protected $_dummyElement;
    protected $_fieldRenderer;
    protected $_values;

    /**
     * @param Varien_Data_Form_Element_Abstract $element
     */
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
            if ($moduleName === 'Mage_Adminhtml' || $moduleName === 'Ffuenf_MageTrashApp' || stripos($moduleName, 'Mage_') !== false) {
                continue;
            }
            $html .= $this->_getFieldHtml($element, $moduleName, $moduleStatus);
        }
        $html .= $this->_getFooterHtml($element);
        return $html;
    }

    /**
     * @return Varien_Object
     */
    protected function _getDummyElement()
    {
        if (empty($this->_dummyElement)) {
            $this->_dummyElement = new Varien_Object(array('show_in_default' => 1, 'show_in_website' => 1));
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
     * @return array
     */
    protected function _getValues()
    {
        if (empty($this->_values)) {
            $this->_values = array(
                array('label' => Mage::helper('adminhtml')->__('Disabled'), 'value' => 0),
                array('label' => Mage::helper('adminhtml')->__('Enabled'), 'value' => 1),
                array('label' => Mage::helper('adminhtml')->__('Uninstall'), 'value' => 2),
            );
        }
        return $this->_values;
    }

    /**
     * @param $fieldset
     * @param $moduleName
     * @param $moduleStatus
     */
    protected function _getFieldHtml($fieldset, $moduleName, $moduleStatus)
    {
        $e = $this->_getDummyElement();
        $field = $fieldset->addField($moduleName, 'select',
            array(
                'name'                  => 'groups[manage_extns][fields][' . $moduleName . '][value]',
                'label'                 => $moduleName,
                'value'                 => (int)$moduleStatus,
                'values'                => $this->_getValues(),
                'inherit'               => true,
                'can_use_default_value' => $this->getForm()->canUseDefaultValue($e),
                'can_use_website_value' => $this->getForm()->canUseWebsiteValue($e),
            ))->setRenderer($this->_getFieldRenderer());
        return $field->toHtml();
    }
}