<?php

namespace Commission\Model\Interfaces;

use Maba\Component\Monetary\Formatting\MoneyFormatter;
use Maba\Component\Monetary\MoneyInterface;

interface OutputStreamInterface extends StreamInterface
{
    /**
     * Output result
     *
     * @param MoneyInterface $money
     * @param MoneyFormatter $formatter
     */
    public function stream(MoneyInterface $money, MoneyFormatter $formatter);
}
