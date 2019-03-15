<?php

namespace Commission\Model\Interfaces;

use Commission\Model\Input;

interface InputStreamInterface extends StreamInterface
{
    /**
     * Map resource entity to Input object
     *
     * @return Input|null
     */
    public function getRow();
}
