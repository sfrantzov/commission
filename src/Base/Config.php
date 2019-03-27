<?php

namespace Commission\Base;

use Commission\Exception\ConfigException;

/**
 * Config class for the application
 */
class Config
{
    /**
     * @var array
     */
    protected $configArray;

    /**
     * @throws ConfigException
     */
    public function __construct()
    {
        $configFile =  __DIR__ . '/../../config/global.config.php';
        if (!is_file($configFile)) {
            throw new ConfigException('Config file config/global.config.php missing');
        }
        $this->configArray = (array) include $configFile;
    }

    /**
     * @param array $configArray
     */
    public function setConfig($configArray)
    {
        $this->configArray = $configArray;
    }

    /**
     * @param string $code
     * @return array|string
     * @throws ConfigException
     */
    public function getConfig($code)
    {
        $configArray = $this->configArray;

        $result = null;
        if (strpos($code, '.') === false) {
            $result = isset($configArray[$code]) ? $configArray[$code] : null;
        } else {
            $paths = explode('.', $code);
            foreach ($paths as $key => $path) {
                if (isset($configArray[$path])) {
                    $value = $configArray[$path];
                    if (is_array($value)) {
                        $configArray = $configArray[$path];
                    }
                    unset($paths[$key]);
                }
            }
            if (count($paths) == 0) {
                $result = $value;
            }
        }

        if ($result === null) {
            throw new ConfigException('Configuration missing:' . $code);
        }

        return $result;
    }
}