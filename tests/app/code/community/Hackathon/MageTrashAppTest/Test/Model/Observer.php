<?php

class Hackathon_MageTrashAppTest_Test_Model_Observer extends EcomDev_PHPUnit_Test_Case_Config {
    /**
     * @var Hackathon_MageTrashApp_Model_Observer
     */
    protected $observerModel = null;

    protected function setUp(){
        $this->observerModel = Mage::getModel('magetrashapp/observer');

        $sessionMock = $this->getModelMockBuilder('adminhtml/session')
            ->disableOriginalConstructor() // This one removes session_start and other methods usage
            ->setMethods(null) // Enables original methods usage, because by default it overrides all methods
            ->getMock();
        $this->replaceByMock('singleton', 'adminhtml/session', $sessionMock);
    }

    /**
     * Test Observer
     * @loadFixture config
     */
    public function testObserver() {
        $resource = Mage::getResourceSingleton('core/resource');
        $number = $resource->getDbVersion('smtppro_setup');

        Mage::log($number);

        //$result = EcomDev_Utils_Reflection::invokeRestrictedMethod( //TODO: fix so doesn't blow up DB!
        //    $this->observerModel, 'saveConfig', array(''));
    }
}