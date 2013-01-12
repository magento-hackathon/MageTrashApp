<?php
class Hackathon_MageTrashApp_Test_Model_Observer extends EcomDev_PHPUnit_Test_Case_Config {
    /**
     * @var Hackathon_MageTrashApp_Model_Observer
     */
    protected $model = null;

    protected function setUp(){
        $this->model = Mage::getModel('magetrashapp/observer');
    }

}