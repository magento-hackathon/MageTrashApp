<?php
require_once 'app/Mage.php';
require_once 'app/code/community/Hackathon/MageTrashApp/Helper/Data.php';
require_once 'PHPUnit/Framework/TestCase.php';

/**
* Hackthon_MageTrashApp_Helper_Data test case.
*/
class DataTest extends PHPUnit_Framework_TestCase
{
    /**
    *
    * @var Hackthon_MageTrashApp_Helper_Data
    */
    private $Hackthon_MageTrashApp_Helper_Data;

    /**
    * Prepares the environment before running a test.
    */
    protected function setUp()
    {
        parent::setUp ();
        // TODO Auto-generated DataTest::setUp()
        $this->Hackthon_MageTrashApp_Helper_Data = new Hackthon_MageTrashApp_Helper_Data(/* parameters */);
    }

    /**
    * Cleans up the environment after running a test.
    */
    protected function tearDown()
    {
        // TODO Auto-generated DataTest::tearDown()
        $this->Hackthon_MageTrashApp_Helper_Data = null;
        parent::tearDown ();
    }

    /**
    * Constructs the test case.
    */
    public function __construct()
    {
        // TODO Auto-generated constructor
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