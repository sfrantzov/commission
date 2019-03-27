<?php

namespace Commission\Logic\BaseLogic;

use Commission\Base\Config;

class BaseLogicConfig extends Config
{

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->configArray = $config;
    }
}