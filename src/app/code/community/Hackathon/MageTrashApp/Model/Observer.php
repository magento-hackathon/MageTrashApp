<?php
class Hackathon_MageTrashApp_Model_Observer extends Mage_Core_Model_Abstract {

        public function test($observer) {
            Mage::log('Damian');
        }


}