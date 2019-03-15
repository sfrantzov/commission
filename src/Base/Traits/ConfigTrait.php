<?php

namespace Commission\Base\Traits;

use Commission\Base\Config;
use Commission\Exception\ConfigException;

trait ConfigTrait
{
    /**
     * @param string $code
     * @return array|string
     * @throws ConfigException
     */
    public function getConfig($code)
    {
        $config = (Config::getInstance());
        $configArray = $config->getConfig();

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
