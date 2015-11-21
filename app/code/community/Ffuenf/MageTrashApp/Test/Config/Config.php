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

class Ffuenf_MageTrashApp_Test_Config_Main extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * Check if the installed module has the correct module version
     *
     * @test
     */
    public function testModuleConfig()
    {
        $this->assertModuleVersionGreaterThanOrEquals($this->expected('module')->getVersion(), 'module is new enough');
        $this->assertModuleCodePool($this->expected('module')->getCodePool(), 'correct module code pool');
        $this->assertModuleIsActive('module is active');
    }

    /**
     * Tests whether extension helper aliases are returning the correct class names
     *
     * @test
     */
    public function testHelperAliases()
    {
        $this->assertHelperAlias(
            'ffuenf_magetrashapp', 'Ffuenf_MageTrashApp_Helper_Data',
            'correct helper alias'
        );
    }

    /**
     * Tests whether extension model aliases are returning the correct class names
     *
     * @test
     */
    public function testModelAliases()
    {
        $this->assertModelAlias(
            'ffuenf_magetrashapp/observer',
            'Ffuenf_MageTrashApp_Model_Observer'
        );
        $this->assertModelAlias(
            'ffuenf_magetrashapp/uninstall',
            'Ffuenf_MageTrashApp_Model_Uninstall'
        );
        $this->assertModelAlias(
            'ffuenf_magetrashapp/pearWrapper',
            'Ffuenf_MageTrashApp_Model_PearWrapper'
        );
        $this->assertModelAlias(
            'ffuenf_magetrashapp/coreResource',
            'Ffuenf_MageTrashApp_Model_CoreResource'
        );
    }

    /**
     * Tests whether extension uses the old-style admin routing (not compatible with SUPEE-6788).
     *
     * @test
     */
    public function testGetOldAdminRouting()
    {
        $routers = Mage::getConfig()->getNode('admin/routers');
        $offendingExtensions = array();
        foreach ($routers[0] as $router) {
            $name = $router->args->module;
            if ($name != 'Mage_Adminhtml') {
                $offendingExtensions[] = $router->args->module;
            }
        }
        $this->assertEquals(
            count($offendingExtensions),
            0,
            'This extension uses old-style admin routing which is not compatible with SUPEE-6788 / Magento 1.9.2.2+'
        );
    }
}