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

class Data extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ffuenf_MageTrashApp_Helper_Data
     */
    protected $_helper;

    public function setUp()
    {
        $this->_helper = new Ffuenf_MageTrashApp_Helper_Data();
    }

    public function tearDown()
    {
        $this->_helper = null;
    }

    /**
     * Tests whether extension is active.
     *
     * @test
     * @covers Ffuenf_MageTrashApp_Helper_Data::isExtensionActive
     */
    public function testIsExtensionActive()
    {
        $this->assertTrue(
            $this->_helper->isExtensionActive(),
            'Extension is not active please check config'
        );
    }
}