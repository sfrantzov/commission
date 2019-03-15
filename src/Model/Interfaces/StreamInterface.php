<?php

namespace Commission\Model\Interfaces;

interface StreamInterface
{
    /**
     * Get resource we can loop
     *
     * @return StreamInterface
     */
    public function getStream();

    /**
     * Close stream
     */
    public function closeStream();
}
