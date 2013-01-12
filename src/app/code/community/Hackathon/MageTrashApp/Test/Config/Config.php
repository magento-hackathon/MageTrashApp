<?php
class Hackathon_MageTrashApp_Test_Config_Config extends EcomDev_PHPUnit_Test_Case_Config {

    public function testObserverAliasExist(){
        $this->assertModelAlias('magetrashapp/observer','Hackathon_MageTrashApp_Model_Observer');
    }

}