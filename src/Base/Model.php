<?php

namespace Commission\Base;

use Commission\Base\Traits\ConfigTrait;
use Commission\Base\Traits\MagicParamsTrait;
use Commission\Base\Traits\MagicTrait;

/**
 * Base Model with magic traits for setting params and accessing properties
 */
abstract class Model
{
    use ConfigTrait;
    use MagicParamsTrait;
    use MagicTrait;
}
