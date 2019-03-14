<?php

namespace Commission\Model\Interfaces;

interface StreamInterface
{
    /**
     * Get resource we can loop
     *
     * @return resource|null
     */
    public function getStream();

    /**
     * Map resource entity to Input object
     *
     * @return Input|null
     */
    public function getRow();
}
