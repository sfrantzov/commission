<?php

namespace Commission\Base;

use Commission\Exception\ConfigException;

class Config
{
    /**
     * @var array
     */
    protected $configArray;

    /**
     * @var array
     */
    private static $instance = null;

    /**
     * @return Config
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    /**
     * @throws ConfigException
     */
    private function __construct()
    {
        $configFile =  __DIR__ . '/../../config/global.config.php';
        if (!is_file($configFile)) {
            throw new ConfigException('Config file config/global.config.php missing');
        }
        $this->configArray = (array) include $configFile;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->configArray;
    }

    /**
     * @param array $configArray
     */
    public function setConfig($configArray)
    {
        $this->configArray = $configArray;
    }
}