<?php


class Hackathon_MageTrashApp_Model_PearWrapper extends Mage_Core_Model_Abstract
{
    const DEFAULT_SCONFIG_FILENAME = 'cache.cfg';

    /**
     * Object of config
     *
     * @var Mage_Connect_Config
     */
    protected $_config;

    /**
     * Object of single config
     *
     * @var Mage_Connect_Singleconfig
     */
    protected $_sconfig;

    /**
     * Object of frontend
     *
     * @var Mage_Connect_Frontend
     */
    protected $_frontend;

    /**
     * Internal cache for command objects
     *
     * @var array
     */
    protected $_cmdCache = array();

    /**
     * Console Started flag
     *
     * @var boolean
     */
    protected $_consoleStarted = false;

    /**
     * Instance of class
     *
     * @var Maged_Connect
     */
    static protected $_instance;

    /**
     * Constructor loads Config, Cache Config and initializes Frontend
     */
    public function __construct()
    {
        $this->getConfig();
        $this->getSingleConfig();
        //$this->getFrontend();
    }

    /**
     * Destructor, sends Console footer if Console started
     */
    public function __destruct()
    {
        if ($this->_consoleStarted) {
            $this->_consoleFooter();
        }
    }

    /**
     * Initialize instance
     *
     * @return Maged_Connect
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * Retrieve object of config and set it to Mage_Connect_Command
     *
     * @return Mage_Connect_Config
     */
    public function getConfig()
    {
        if (!$this->_config) {
            $this->_config = new Mage_Connect_Config();
            $ftp=$this->_config->__get('remote_config');
            if(!empty($ftp)){
                $packager = new Mage_Connect_Packager();
                list($cache, $config, $ftpObj) = $packager->getRemoteConf($ftp);
                $this->_config=$config;
                $this->_sconfig=$cache;
            }
            $this->_config->magento_root = dirname(dirname(__FILE__)).DS.'..';
            //Mage_Connect_Command::setConfigObject($this->_config);
        }
        return $this->_config;
    }

    /**
     * Retrieve object of single config and set it to Mage_Connect_Command
     *
     * @param bool $reload
     * @return Mage_Connect_Singleconfig
     */
    public function getSingleConfig($reload = false)
    {
        if(!$this->_sconfig || $reload) {
            $this->_sconfig = new Mage_Connect_Singleconfig(

                Mage::getModuleDir('etc','Hackathon_MageTrashApp').DIRECTORY_SEPARATOR
                    . self::DEFAULT_SCONFIG_FILENAME
            );
        }
        Mage_Connect_Command::setSconfig($this->_sconfig);
        return $this->_sconfig;

    }

    /**
     * Retrieve object of frontend and set it to Mage_Connect_Command
     *
     * @return Maged_Connect_Frontend
     */
    public function getFrontend()
    {
        if (!$this->_frontend) {
            $this->_frontend = new Maged_Connect_Frontend();
            Mage_Connect_Command::setFrontendObject($this->_frontend);
        }
        return $this->_frontend;
    }

    /**
     * Retrieve lof from frontend
     *
     * @return array
     */
    public function getLog()
    {
        return $this->getFrontend()->getLog();
    }

    /**
     * Retrieve output from frontend
     *
     * @return array
     */
    public function getOutput()
    {
        return $this->getFrontend()->getOutput();
    }

    /**
     * Clean registry
     *
     * @return Maged_Connect
     */
    public function cleanSconfig()
    {
        $this->getSingleConfig()->clear();
        return $this;
    }

    /**
     * Delete directory recursively
     *
     * @param string $path
     * @return Maged_Connect
     */
    public function delTree($path) {
        if (@is_dir($path)) {
            $entries = @scandir($path);
            foreach ($entries as $entry) {
                if ($entry != '.' && $entry != '..') {
                    $this->delTree($path.DS.$entry);
                }
            }
            @rmdir($path);
        } else {
            @unlink($path);
        }
        return $this;
    }

    /**
     * Run commands from Mage_Connect_Command
     *
     * @param string $command
     * @param array $options
     * @param array $params
     * @return boolean|Mage_Connect_Error
     */
    public function run($command, $options=array(), $params=array())
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '256M');

        if (empty($this->_cmdCache[$command])) {
            Mage_Connect_Command::getCommands();
            /**
             * @var $cmd Mage_Connect_Command
             */
            $cmd = Mage_Connect_Command::getInstance($command);
            if ($cmd instanceof Mage_Connect_Error) {
                return $cmd;
            }
            $this->_cmdCache[$command] = $cmd;
        } else {
            /**
             * @var $cmd Mage_Connect_Command
             */
            $cmd = $this->_cmdCache[$command];
        }
        $ftp=$this->getConfig()->remote_config;
        if(strlen($ftp)>0){
            $options=array_merge($options, array('ftp'=>$ftp));
        }
        $cmd->run($command, $options, $params);
        if ($cmd->ui()->hasErrors()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Set remote Config by URI
     *
     * @param $uri
     * @return Maged_Connect
     */
    public function setRemoteConfig($uri)
    {
        $this->getConfig()->remote_config=$uri;
        return $this;
    }


}
